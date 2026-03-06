from flask import Blueprint, render_template

main = Blueprint('main', __name__)

@main.route("/")
def index():
    return render_template("index.html")

# --- NUEVA RUTA: DASHBOARD ---
@main.route("/dashboard")
def dashboard():
    return render_template("dashboard.html")

@main.route("/editar_autoparte")
def editar_autoparte():
    return render_template("editar_autoparte.html")

@main.route("/gestion_inventario")
def gestion_inventario():
    return render_template("gestion_inventario.html")

@main.route("/pedidos")
def pedidos():
    return render_template("pedidos.html")

# --- NUEVA RUTA: DETALLE DE PEDIDO ---
# Nota: Usamos el nombre físico 'datalle_pedido.html' que aparece en tus archivos
@main.route("/detalle_pedido")
def detalle_pedido():
    return render_template("datalle_pedido.html")

@main.route("/perfil")
def perfil():
    return render_template("perfil.html")

@main.route("/productos")
def productos():
    return render_template("productos.html")

@main.route("/recuperar")
def recuperar():
    return render_template("recuperar_clave.html")

@main.route("/registrar_autoparte")
def registrar_autoparte():
    return render_template("registrar_autoparte.html")

@main.route("/reporte_clientes")
def reporte_clientes():
    return render_template("reporte_clientes.html")

@main.route("/reportes_pedidos")
def reportes_pedidos():
    return render_template("reportes_pedidos.html")

@main.route("/actualizar_stock")
def actualizar_stock():
    return render_template("actualizar_stock.html")