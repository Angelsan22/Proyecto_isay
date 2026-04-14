"""
Rutas de gestión de inventario y productos.
"""
from flask import Blueprint, render_template, request, redirect, url_for, flash
from flask_login import login_required

from app.services.api_client import ApiClient

inventario = Blueprint('inventario', __name__)


@inventario.route("/gestion_inventario")
@login_required
def gestion_inventario():
    nombre = request.args.get("nombre", "")
    categoria = request.args.get("categoria", "Todas las categorías")

    productos_data = []
    try:
        # Petición a la API central con filtros
        params = {"nombre": nombre, "categoria": categoria}
        response = ApiClient.get_productos(params)
        if response.status_code == 200:
            productos_data = response.json()
        else:
            flash("Error al obtener inventario de la API", "warning")
    except Exception as e:
        print(f"Error conexión API Inventario: {e}")
        flash("No se pudo conectar con el servidor de inventario", "danger")

    # Estadísticas para el dashboard de inventario con seguridad ante errores de llaves
    stats = {
        "total_articulos": len(productos_data),
        "total_unidades": sum([p.get("stock_actual", 0) for p in productos_data]),
        "bajo_stock": len([p for p in productos_data if p.get("stock_actual", 0) <= p.get("stock_minimo", 0)]),
        "valoracion_total": sum([p.get("stock_actual", 0) * p.get("precio", 0) for p in productos_data]),
        "categorias": len(set([p.get("categoria", "Sin Categoría") for p in productos_data if p.get("categoria")]))
    }

    return render_template("gestion_inventario.html", productos=productos_data,
                           filtro_nombre=nombre, filtro_categoria=categoria,
                           stats=stats)


@inventario.route("/productos")
@login_required
def productos():
    nombre = request.args.get("nombre", "")
    categoria = request.args.get("categoria", "Todas las categorías")

    productos_data = []
    try:
        # Petición a la API central para el catálogo
        params = {"nombre": nombre, "categoria": categoria}
        response = ApiClient.get_productos(params)
        if response.status_code == 200:
            productos_data = response.json()
        else:
            flash("Error al obtener catálogo de la API", "warning")
    except Exception as e:
        print(f"Error conexión API Catálogo: {e}")
        flash("No se pudo conectar con el servidor de catálogo", "danger")

    # Estadísticas para el encabezado del catálogo
    stats = {
        "total": len(productos_data),
        "categorias": len(set([p.get("categoria", "N/A") for p in productos_data if p.get("categoria")])),
        "precio_promedio": sum([p.get("precio", 0) for p in productos_data]) / len(productos_data) if productos_data else 0,
        "agotados": len([p for p in productos_data if p.get("stock_actual", 0) <= 0])
    }

    return render_template("productos.html", productos=productos_data,
                           filtro_nombre=nombre, filtro_categoria=categoria,
                           stats=stats)


@inventario.route("/registrar_autoparte", methods=["GET", "POST"])
@login_required
def registrar_autoparte():
    if request.method == "POST":
        nombre = request.form.get("nombre")
        marca = request.form.get("marca")
        if marca == "otra":
            marca = request.form.get("nueva_marca")

        estado = request.form.get("estado")
        precio = request.form.get("precio")
        stock = request.form.get("stock")
        descripcion = request.form.get("descripcion")

        if not nombre or not precio or not stock or not marca:
            flash("Por favor completa los campos obligatorios.", "warning")
            return redirect(url_for('inventario.registrar_autoparte'))

        try:
            payload = {
                "nombre": f"{nombre} ({marca})",  # Include marca in nombre for now
                "categoria": "Autopartes",
                "precio": float(precio),
                "stock_actual": int(stock),
                "stock_minimo": 10,
                "descripcion": descripcion
            }

            response = ApiClient.create_producto(payload)
            if response.status_code in [200, 201]:
                flash(f"¡Autoparte '{nombre}' registrada exitosamente!", "success")
                return redirect(url_for('inventario.gestion_inventario'))
            else:
                flash("Error al procesar el registro en el servidor central.", "danger")
        except Exception as e:
            print(f"Error registrando producto: {e}")
            flash("No se pudo conectar con el servidor de inventario.", "danger")

        return redirect(url_for('inventario.registrar_autoparte'))

    return render_template("registrar_autoparte.html")


@inventario.route("/editar_autoparte")
@login_required
def editar_autoparte():
    return render_template("editar_autoparte.html")


@inventario.route("/actualizar_stock/<int:id>", methods=["GET", "POST"])
@login_required
def actualizar_stock(id):
    producto = None
    try:
        # Recuperar datos del producto actual
        response = ApiClient.get_producto(id)
        if response.status_code == 200:
            producto = response.json()
        else:
            flash("Producto no encontrado", "danger")
            return redirect(url_for('inventario.gestion_inventario'))
    except Exception as e:
        flash("Error de conexión con la API", "danger")
        return redirect(url_for('inventario.gestion_inventario'))

    if request.method == "POST":
        nueva_cantidad = request.form.get("nueva_cantidad")
        nuevo_precio = request.form.get("precio")
        nueva_descripcion = request.form.get("descripcion")
        motivo = request.form.get("motivo")

        if not nueva_cantidad or not nuevo_precio:
            flash("Debes completar todos los campos obligatorios", "warning")
        else:
            try:
                # El esquema espera todos los campos, así que enviamos el objeto actualizado
                payload = producto.copy()
                payload["stock_actual"] = int(nueva_cantidad)
                payload["precio"] = float(nuevo_precio)
                payload["descripcion"] = nueva_descripcion

                update_response = ApiClient.update_producto(id, payload)

                if update_response.status_code == 200:
                    flash(f"¡Stock de '{producto['nombre']}' actualizado a {nueva_cantidad}!", "success")
                    return redirect(url_for('inventario.gestion_inventario'))
                else:
                    flash("No se pudo actualizar el stock en el servidor", "danger")
            except Exception as e:
                flash(f"Error al procesar actualización: {e}", "danger")

    return render_template("actualizar_stock.html", producto=producto)
