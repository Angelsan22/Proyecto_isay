from flask import Blueprint, render_template, request, redirect, url_for, flash
from flask_login import login_user, logout_user, login_required, current_user
from app.models import Admin
from app import db

main = Blueprint('main', __name__)

@main.route("/", methods=["GET", "POST"])
def index():
    if current_user.is_authenticated:
        return redirect(url_for('main.dashboard'))
    
    if request.method == "POST":
        email = request.form.get("email")
        password = request.form.get("password")
        
        admin = Admin.query.filter_by(email=email).first()
        if admin and admin.check_password(password):
            login_user(admin)
            return redirect(url_for('main.dashboard'))
        else:
            flash("Credenciales incorrectas. Por favor intentalo de nuevo.", "danger")
            
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
        email = request.form.get("email")
        password = request.form.get("password")
        
        if Admin.query.filter_by(email=email).first():
            flash("El correo ya está registrado.", "danger")
        else:
            nuevo_admin = Admin(email=email)
            nuevo_admin.set_password(password)
            db.session.add(nuevo_admin)
            db.session.commit()
            flash("Administrador creado exitosamente.", "success")
            return redirect(url_for('main.dashboard'))
            
    return render_template("crear_admin.html")

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
    # Recuperación no está protegida por lógica, usualmente se accedería sin iniciar sesión.
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