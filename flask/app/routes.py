from flask import Blueprint, render_template, request, redirect, url_for, flash
from flask_login import login_user, logout_user, login_required, current_user
import requests
from app.models import AdminSession
from datetime import datetime

main = Blueprint('main', __name__)
API_URL = "http://127.0.0.1:8000"

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

    return render_template("crear_admin.html", admins=admins_creados, usuarios=usuarios_creados)

# --- RUTAS EXISTENTES PROTEGIDAS ---

@main.route("/dashboard")
@login_required
def dashboard():
    return render_template("dashboard.html")

@main.route("/editar_autoparte")
@login_required
def editar_autoparte():
    return render_template("editar_autoparte.html")

@main.route("/gestion_inventario")
@login_required
def gestion_inventario():
    return render_template("gestion_inventario.html")

@main.route("/pedidos")
@login_required
def pedidos():
    return render_template("pedidos.html")

@main.route("/datalle_pedido")
@login_required
def detalle_pedido():
    return render_template("datalle_pedido.html")

@main.route("/perfil")
@login_required
def perfil():
    return render_template("perfil.html")

@main.route("/productos")
@login_required
def productos():
    return render_template("productos.html")

@main.route("/recuperar")
def recuperar():
    if current_user.is_authenticated:
        return redirect(url_for('main.dashboard'))
    return render_template("recuperar_clave.html")

@main.route("/registrar_autoparte")
@login_required
def registrar_autoparte():
    return render_template("registrar_autoparte.html")

@main.route("/reporte_clientes")
@login_required
def reporte_clientes():
    return render_template("reporte_clientes.html")

@main.route("/reportes_pedidos")
@login_required
def reportes_pedidos():
    return render_template("reportes_pedidos.html")

@main.route("/actualizar_stock")
@login_required
def actualizar_stock():
    return render_template("actualizar_stock.html")