from flask import Blueprint, render_template, request, redirect, url_for, flash, current_app
from flask_login import login_user, logout_user, login_required, current_user
import requests
from app.models import AdminSession
from datetime import datetime
from werkzeug.utils import secure_filename
import uuid
import os

main = Blueprint('main', __name__)
API_URL = os.environ.get("FASTAPI_URL", "http://127.0.0.1:8000")

@main.route("/", methods=["GET", "POST"])
def index():
    if current_user.is_authenticated:
        return redirect(url_for('main.dashboard'))
    
    if request.method == "POST":
        email = request.form.get("email")
        password = request.form.get("password")
        
        try:
            response = requests.post(f"{API_URL}/admins/login", json={"email": email, "password": password})
            if response.status_code == 200:
                data = response.json()
                admin_user = AdminSession(data["admin_id"], data["nombre"], data["email"])
                login_user(admin_user)
                return redirect(url_for('main.dashboard'))
            elif response.status_code == 401:
                flash("Credenciales incorrectas. Por favor intentalo de nuevo.", "danger")
            else:
                flash("Error en el servidor de autenticación.", "danger")
        except Exception as e:
            flash("No se pudo contactar al servidor de la API.", "danger")
            print(e)
            
    return render_template("index.html")

@main.route("/logout")
@login_required
def logout():
    logout_user()
    return redirect(url_for('main.index'))

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
            response = requests.post(f"{API_URL}/admins/", json=payload)
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
        r_admins = requests.get(f"{API_URL}/admins/")
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
                
        r_usuarios = requests.get(f"{API_URL}/usuarios/")
        if r_usuarios.status_code == 200:
            usuarios_creados = r_usuarios.json()
            for u in usuarios_creados:
                u['fecha_registro'] = datetime.fromisoformat(u['fecha_registro'])
    except Exception as e:
        flash("Hubo un problema recuperando los registros.", "danger")
        print(e)

    return render_template("admin/crear_admin.html", admins=admins_creados, usuarios=usuarios_creados)

# --- RUTAS EXISTENTES PROTEGIDAS ---

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
        r_pedidos = requests.get(f"{API_URL}/pedidos/")
        if r_pedidos.status_code == 200:
            pedidos = r_pedidos.json()
            stats["pendientes"] = len([p for p in pedidos if p["estatus"] == "En Proceso"])
            stats["en_proceso"] = len([p for p in pedidos if p["estatus"] == "Enviado"])
            stats["completados"] = len([p for p in pedidos if p["estatus"] == "Entregado"])
        
        # 2. Obtener Productos y detectar Stock Bajo
        r_productos = requests.get(f"{API_URL}/productos/")
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

    return render_template("admin/dashboard.html", stats=stats)

@main.route("/editar_autoparte")
@login_required
def editar_autoparte():
    return render_template("admin/editar_autoparte.html")

@main.route("/gestion_inventario")
@login_required
def gestion_inventario():
    nombre = request.args.get("nombre", "")
    categoria = request.args.get("categoria", "Todas las categorías")
    
    productos_data = []
    try:
        # Petición a la API central con filtros
        params = {"nombre": nombre, "categoria": categoria}
        response = requests.get(f"{API_URL}/productos/", params=params)
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

    return render_template("admin/gestion_inventario.html", productos=productos_data,
                           filtro_nombre=nombre, filtro_categoria=categoria,
                           stats=stats)

@main.route("/pedidos")
@login_required
def pedidos():
    cliente = request.args.get("usuario", "")
    estatus = request.args.get("estatus", "Todos los pedidos")
    
    pedidos_data = []
    try:
        # Petición a la API central con filtros
        params = {"cliente": cliente, "estatus": estatus}
        response = requests.get(f"{API_URL}/pedidos/", params=params)
        if response.status_code == 200:
            pedidos_data = response.json()
            
            # De forma simplificada, vamos a pedir la info de los usuarios para mostrar nombres
            # (En producción esto se haría con un join en la API, pero aquí para ser explícitos:)
            for p in pedidos_data:
                user_res = requests.get(f"{API_URL}/usuarios/") # Podríamos filtrar por ID pero esto es una demo
                if user_res.status_code == 200:
                    users = user_res.json()
                    user = next((u for u in users if u["id"] == p["cliente_id"]), None)
                    p["cliente_nombre"] = user["nombre"] if user else "Desconocido"
        else:
            flash("Error al obtener pedidos de la API", "warning")
    except Exception as e:
        print(f"Error conexión API Pedidos: {e}")
        flash("No se pudo conectar con el servidor de pedidos", "danger")

    # Estadísticas para las tarjetas del dashboard
    stats = {
        "total": len(pedidos_data),
        "proceso": len([p for p in pedidos_data if p["estatus"] == "En Proceso"]),
        "enviado": len([p for p in pedidos_data if p["estatus"] == "Enviado"]),
        "entregado": len([p for p in pedidos_data if p["estatus"] == "Entregado"]),
        "cancelado": len([p for p in pedidos_data if p["estatus"] == "Cancelado"]),
        "monto_total": sum([p["total"] for p in pedidos_data])
    }

    return render_template("admin/pedidos.html", pedidos=pedidos_data, 
                           filtro_cliente=cliente, filtro_estatus=estatus,
                           stats=stats)

@main.route("/pedido/<int:id>")
@login_required
def detalle_pedido(id):
    try:
        response = requests.get(f"{API_URL}/pedidos/")
        if response.status_code == 200:
            pedidos = response.json()
            # Encontrar el pedido específico
            pedido = next((p for p in pedidos if p["id"] == id), None)
            
            if not pedido:
                flash("Pedido no encontrado.", "danger")
                return redirect(url_for('main.pedidos'))

            # Obtener info del cliente
            user_res = requests.get(f"{API_URL}/usuarios/")
            if user_res.status_code == 200:
                users = user_res.json()
                user = next((u for u in users if u["id"] == pedido["cliente_id"]), None)
                pedido["cliente"] = user
            
            # Obtener nombres de productos para los items
            r_prods = requests.get(f"{API_URL}/productos/")
            if r_prods.status_code == 200:
                productos_map = {p["id"]: p for p in r_prods.json()}
                for item in pedido.get("detalles", []):
                    prod_info = productos_map.get(item["producto_id"])
                    item["nombre_producto"] = prod_info["nombre"] if prod_info else "Producto #"+str(item["producto_id"])

            return render_template("admin/datalle_pedido.html", pedido=pedido)
    except Exception as e:
        print(f"Error fetching order detail: {e}")
        flash("Error de conexión con la API.", "danger")
    
    return redirect(url_for('main.pedidos'))

@main.route("/pedido/<int:id>/estatus", methods=["POST"])
@login_required
def actualizar_estatus_pedido(id):
    nuevo_estatus = request.form.get("estatus")
    try:
        response = requests.patch(
            f"{API_URL}/pedidos/{id}/estatus",
            json={"estatus": nuevo_estatus}
        )
        if response.status_code == 200:
            flash(f"Estatus actualizado a '{nuevo_estatus}'.", "success")
        else:
            flash("No se pudo actualizar el estatus.", "warning")
    except Exception as e:
        flash("Error de conexion con la API.", "danger")
    return redirect(url_for('main.detalle_pedido', id=id))

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
        r_productos = requests.get(f"{API_URL}/productos/")
        if r_productos.status_code == 200:
            stats["productos_totales"] = len(r_productos.json())
        
        r_pedidos = requests.get(f"{API_URL}/pedidos/")
        if r_pedidos.status_code == 200:
            stats["pedidos_gestionados"] = len(r_pedidos.json())

        r_usuarios = requests.get(f"{API_URL}/usuarios/")
        if r_usuarios.status_code == 200:
            stats["clientes_activos"] = len(r_usuarios.json())
            
    except Exception as e:
        print(f"Error cargando estadísticas de perfil: {e}")

    return render_template("admin/perfil.html", stats=stats)

@main.route("/productos")
@login_required
def productos():
    nombre = request.args.get("nombre", "")
    categoria = request.args.get("categoria", "Todas las categorías")
    
    productos_data = []
    try:
        # Petición a la API central para el catálogo
        params = {"nombre": nombre, "categoria": categoria}
        response = requests.get(f"{API_URL}/productos/", params=params)
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

    return render_template("admin/productos.html", productos=productos_data,
                           filtro_nombre=nombre, filtro_categoria=categoria,
                           stats=stats)

@main.route("/recuperar")
def recuperar():
    if current_user.is_authenticated:
        return redirect(url_for('main.dashboard'))
    return render_template("recuperar_clave.html")

@main.route("/registrar_autoparte", methods=["GET", "POST"])
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
        imagen_file = request.files.get("imagen")
        
        if not nombre or not precio or not stock or not marca:
            flash("Por favor completa los campos obligatorios.", "warning")
            return redirect(url_for('main.registrar_autoparte'))
            
        try:
            imagen_path = None
            if imagen_file and imagen_file.filename:
                filename = secure_filename(imagen_file.filename)
                unique_filename = f"{uuid.uuid4().hex}_{filename}"
                upload_folder = os.path.join(current_app.root_path, 'static', 'uploads')
                os.makedirs(upload_folder, exist_ok=True)
                file_path = os.path.join(upload_folder, unique_filename)
                imagen_file.save(file_path)
                imagen_path = f"uploads/{unique_filename}"

            payload = {
                "nombre": f"{nombre} ({marca})", # Include marca in nombre for now if model doesn't support it directly
                "categoria": "Autopartes",
                "precio": float(precio),
                "stock_actual": int(stock),
                "stock_minimo": 10,
                "descripcion": descripcion,
                "imagen": imagen_path
            }
            
            response = requests.post(f"{API_URL}/productos/", json=payload)
            if response.status_code in [200, 201]:
                flash(f"¡Autoparte '{nombre}' registrada exitosamente!", "success")
                return redirect(url_for('main.gestion_inventario'))
            else:
                flash("Error al procesar el registro en el servidor central.", "danger")
        except Exception as e:
            print(f"Error registrando producto: {e}")
            flash("No se pudo conectar con el servidor de inventario.", "danger")
            
        return redirect(url_for('main.registrar_autoparte'))
        
    return render_template("admin/registrar_autoparte.html")

@main.route("/reporte_clientes")
@login_required
def reporte_clientes():
    stats = {
        "clientes_totales": 0,
        "pedidos_totales": 0,
        "productos_stock": 0,
        "valor_inventario": 0,
        "top_clientes": []
    }
    
    try:
        # 1. Obtener Clientes
        r_clientes = requests.get(f"{API_URL}/usuarios/")
        if r_clientes.status_code == 200:
            clientes = r_clientes.json()
            stats["clientes_totales"] = len(clientes)
            # Mock de top clientes basado en la lista real (para la tabla)
            for i, c in enumerate(clientes[:10]):
                stats["top_clientes"].append({
                    "nombre": c["nombre"],
                    "siglas": "".join([n[0] for n in c["nombre"].split()[:2]]).upper(),
                    "pedidos": 10 - i, # Simulado por ahora hasta tener joins
                    "total": (10 - i) * 1200
                })
        
        # 2. Obtener Pedidos
        r_pedidos = requests.get(f"{API_URL}/pedidos/")
        if r_pedidos.status_code == 200:
            stats["pedidos_totales"] = len(r_pedidos.json())
            
        # 3. Obtener Inventario
        r_productos = requests.get(f"{API_URL}/productos/")
        if r_productos.status_code == 200:
            productos = r_productos.json()
            stats["productos_stock"] = len(productos)
            stats["valor_inventario"] = sum(p["precio"] * p["stock_actual"] for p in productos)
            
    except Exception as e:
        print(f"Error cargando reportes: {e}")
        flash("Error al conectar con la base de datos para los reportes", "warning")

    return render_template("admin/reporte_clientes.html", stats=stats)

@main.route("/reportes_pedidos")
@login_required
def reportes_pedidos():
    return render_template("admin/reportes_pedidos.html")

@main.route("/actualizar_stock/<int:id>", methods=["GET", "POST"])
@login_required
def actualizar_stock(id):
    producto = None
    try:
        # Recuperar datos del producto actual
        response = requests.get(f"{API_URL}/productos/{id}")
        if response.status_code == 200:
            producto = response.json()
        else:
            flash("Producto no encontrado", "danger")
            return redirect(url_for('main.gestion_inventario'))
    except Exception as e:
        flash("Error de conexión con la API", "danger")
        return redirect(url_for('main.gestion_inventario'))

    if request.method == "POST":
        nueva_cantidad = request.form.get("nueva_cantidad")
        nuevo_precio = request.form.get("precio")
        nueva_descripcion = request.form.get("descripcion")
        motivo = request.form.get("motivo")
        imagen_file = request.files.get("imagen")
        
        if not nueva_cantidad or not nuevo_precio:
            flash("Debes completar todos los campos obligatorios", "warning")
        else:
            try:
                # El esquema espera todos los campos, así que enviamos el objeto actualizado
                payload = producto.copy()
                payload["stock_actual"] = int(nueva_cantidad)
                payload["precio"] = float(nuevo_precio)
                payload["descripcion"] = nueva_descripcion
                
                if imagen_file and imagen_file.filename:
                    filename = secure_filename(imagen_file.filename)
                    unique_filename = f"{uuid.uuid4().hex}_{filename}"
                    upload_folder = os.path.join(current_app.root_path, 'static', 'uploads')
                    os.makedirs(upload_folder, exist_ok=True)
                    file_path = os.path.join(upload_folder, unique_filename)
                    imagen_file.save(file_path)
                    payload["imagen"] = f"uploads/{unique_filename}"
                
                # Quitar campos que la API no espera en el body si es necesario (id, etc)
                # En FastAPI models.ProductoCreate no tiene ID
                update_response = requests.put(f"{API_URL}/productos/{id}", json=payload)
                
                if update_response.status_code == 200:
                    flash(f"¡Stock de '{producto['nombre']}' actualizado a {nueva_cantidad}!", "success")
                    return redirect(url_for('main.gestion_inventario'))
                else:
                    flash("No se pudo actualizar el stock en el servidor", "danger")
            except Exception as e:
                flash(f"Error al procesar actualización: {e}", "danger")

    return render_template("admin/actualizar_stock.html", producto=producto)