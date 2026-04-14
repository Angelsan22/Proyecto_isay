"""
Rutas principales: dashboard, perfil, gestión de admins.
"""
from flask import Blueprint, render_template, request, redirect, url_for, flash
from flask_login import login_required, current_user
from datetime import datetime

from app.services.api_client import ApiClient

main = Blueprint('main', __name__)


@main.route("/dashboard")
@login_required
def dashboard():
    stats = {
        "pendientes": 0,
        "en_proceso": 0,
        "stock_bajo": 0,
        "completados": 0,
        "top_productos": []
    }

    try:
        # 1. Obtener Pedidos y clasificar por estatus
        r_pedidos = ApiClient.get_pedidos()
        if r_pedidos.status_code == 200:
            pedidos = r_pedidos.json()
            stats["pendientes"] = len([p for p in pedidos if p["estatus"] == "En Proceso"])
            stats["en_proceso"] = len([p for p in pedidos if p["estatus"] == "Enviado"])
            stats["completados"] = len([p for p in pedidos if p["estatus"] == "Entregado"])

        # 2. Obtener Productos y detectar Stock Bajo
        r_productos = ApiClient.get_productos()
        if r_productos.status_code == 200:
            productos = r_productos.json()
            # Identificar productos que necesitan reabastecimiento (Stock Bajo)
            stats["stock_bajo_lista"] = [p for p in productos if p["stock_actual"] <= p.get("stock_minimo", 0)]
            stats["stock_bajo"] = len(stats["stock_bajo_lista"])

            # Tomar los 5 productos más valiosos o con más stock como "Top"
            stats["top_productos"] = sorted(productos, key=lambda x: x["precio"], reverse=True)[:5]

    except Exception as e:
        print(f"Error cargando dashboard real: {e}")
        flash("La conexión con el servidor de datos ha fallado. Usando datos de respaldo.", "warning")

    return render_template("dashboard.html", stats=stats)


@main.route("/crear_admin", methods=["GET", "POST"])
@login_required
def crear_admin():
    if request.method == "POST":
        nombre = request.form.get("nombre")
        email = request.form.get("email")
        password = request.form.get("password")

        try:
            payload = {
                "nombre": nombre,
                "email": email,
                "password": password,
                "creador_id": int(current_user.id)
            }
            response = ApiClient.create_admin(payload)
            if response.status_code == 200:
                flash("Administrador creado exitosamente.", "success")
            elif response.status_code == 400:
                flash("El correo ya está registrado.", "danger")
            else:
                flash("Error al crear el administrador en la API.", "danger")
        except Exception as e:
            flash("Error de conexión con la API.", "danger")
            print(e)

        return redirect(url_for('main.crear_admin'))

    # Obtener listas combinadas
    admins_creados = []
    usuarios_creados = []

    try:
        r_admins = ApiClient.get_admins()
        if r_admins.status_code == 200:
            admins_creados = r_admins.json()
            # Convert string dates to datetime objects for jinja
            for a in admins_creados:
                a['fecha_creacion'] = datetime.fromisoformat(a['fecha_creacion'])

            # Map creador_id to creador dict for the template
            admin_dict = {a['id']: a for a in admins_creados}
            for a in admins_creados:
                if a.get('creador_id'):
                    creador = admin_dict.get(a['creador_id'])
                    if creador:
                         a['creador_info'] = f"{creador['nombre']} ({creador['email']})"
                else:
                    a['creador_info'] = "(Sistema o Inicial)"

        r_usuarios = ApiClient.get_usuarios()
        if r_usuarios.status_code == 200:
            usuarios_creados = r_usuarios.json()
            for u in usuarios_creados:
                u['fecha_registro'] = datetime.fromisoformat(u['fecha_registro'])
    except Exception as e:
        flash("Hubo un problema recuperando los registros.", "danger")
        print(e)

    return render_template("crear_admin.html", admins=admins_creados, usuarios=usuarios_creados)


@main.route("/perfil")
@login_required
def perfil():
    # Obtener algunas estadísticas básicas para que el perfil se vea más completo
    stats = {
        "productos_totales": 0,
        "pedidos_gestionados": 0,
        "clientes_activos": 0
    }
    try:
        r_productos = ApiClient.get_productos()
        if r_productos.status_code == 200:
            stats["productos_totales"] = len(r_productos.json())

        r_pedidos = ApiClient.get_pedidos()
        if r_pedidos.status_code == 200:
            stats["pedidos_gestionados"] = len(r_pedidos.json())

        r_usuarios = ApiClient.get_usuarios()
        if r_usuarios.status_code == 200:
            stats["clientes_activos"] = len(r_usuarios.json())

    except Exception as e:
        print(f"Error cargando estadísticas de perfil: {e}")

    return render_template("perfil.html", stats=stats)
