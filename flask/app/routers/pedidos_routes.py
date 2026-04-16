"""
Rutas de gestión de pedidos y reportes.
"""
from flask import Blueprint, render_template, request, flash, make_response
from flask_login import login_required

from app.services.api_client import ApiClient
from app.services.pdf_service import generate_report

pedidos_bp = Blueprint('pedidos', __name__)


@pedidos_bp.route("/pedidos")
@login_required
def pedidos():
    cliente = request.args.get("usuario", "")
    estatus = request.args.get("estatus", "Todos los pedidos")

    pedidos_data = []
    try:
        # Petición a la API central con filtros
        params = {"cliente": cliente, "estatus": estatus}
        response = ApiClient.get_pedidos(params)
        if response.status_code == 200:
            pedidos_data = response.json()

            # Pedir la info de los usuarios para mostrar nombres
            for p in pedidos_data:
                user_res = ApiClient.get_usuarios()
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
        "monto_total": sum([p["total"] for p in pedidos_data])
    }

    return render_template("admin/pedidos.html", pedidos=pedidos_data,
                           filtro_cliente=cliente, filtro_estatus=estatus,
                           stats=stats)


@pedidos_bp.route("/datalle_pedido")
@login_required
def detalle_pedido():
    return render_template("admin/datalle_pedido.html")


@pedidos_bp.route("/reportes_pedidos")
@login_required
def reportes_pedidos():
    return render_template("admin/reportes_pedidos.html")


@pedidos_bp.route("/reporte_clientes")
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
        r_clientes = ApiClient.get_usuarios()
        if r_clientes.status_code == 200:
            clientes = r_clientes.json()
            stats["clientes_totales"] = len(clientes)
            # Mock de top clientes basado en la lista real (para la tabla)
            for i, c in enumerate(clientes[:10]):
                stats["top_clientes"].append({
                    "nombre": c["nombre"],
                    "siglas": "".join([n[0] for n in c["nombre"].split()[:2]]).upper(),
                    "pedidos": 10 - i,  # Simulado por ahora hasta tener joins
                    "total": (10 - i) * 1200
                })

        # 2. Obtener Pedidos
        r_pedidos = ApiClient.get_pedidos()
        if r_pedidos.status_code == 200:
            stats["pedidos_totales"] = len(r_pedidos.json())

        # 3. Obtener Inventario
        r_productos = ApiClient.get_productos()
        if r_productos.status_code == 200:
            productos = r_productos.json()
            stats["productos_stock"] = len(productos)
            stats["valor_inventario"] = sum(p["precio"] * p["stock_actual"] for p in productos)

    except Exception as e:
        print(f"Error cargando reportes: {e}")
        flash("Error al conectar con la base de datos para los reportes", "warning")

    return render_template("admin/reporte_clientes.html", stats=stats)
 
@pedidos_bp.route("/pedido/<int:id>/estatus", methods=["POST"])
@login_required
def actualizar_estatus_pedido(id):
    nuevo_estatus = request.form.get("estatus")
    try:
        response = ApiClient.update_pedido_estatus(id, nuevo_estatus)
        if response.status_code == 200:
            flash(f"Estatus actualizado a '{nuevo_estatus}'.", "success")
        else:
            flash("No se pudo actualizar el estatus.", "warning")
    except Exception as e:
        flash("Error de conexion con la API.", "danger")
    return redirect(url_for('pedidos.detalle_pedido', id=id))


@pedidos_bp.route("/descargar_reporte_pedidos")
@login_required
def descargar_reporte_pedidos():
    cliente = request.args.get("usuario", "")
    estatus = request.args.get("estatus", "Todos los pedidos")

    pedidos_data = []
    try:
        params = {"cliente": cliente, "estatus": estatus}
        response = ApiClient.get_pedidos(params)
        if response.status_code == 200:
            pedidos_data = response.json()
            users_res = ApiClient.get_usuarios()
            if users_res.status_code == 200:
                users = {u["id"]: u["nombre"] for u in users_res.json()}
                for p in pedidos_data:
                    p["cliente_nombre"] = users.get(p["cliente_id"], "Desconocido")
    except Exception as e:
        print(f"Error PDF Pedidos: {e}")

    headers = ["ID Pedido", "Cliente", "Estatus", "Total"]
    widths = [30, 80, 40, 40]
    data = []
    for p in pedidos_data:
        data.append([
            f"#{p.get('id', 0)}",
            p.get('cliente_nombre', 'N/A'),
            p.get('estatus', 'N/A'),
            f"${p.get('total', 0):,.2f}"
        ])

    pdf_output = generate_report("Reporte General de Pedidos", headers, data, widths)
    
    response = make_response(pdf_output)
    response.headers['Content-Type'] = 'application/pdf'
    response.headers['Content-Disposition'] = 'attachment; filename=reporte_pedidos.pdf'
    return response


@pedidos_bp.route("/descargar_reporte_clientes")
@login_required
def descargar_reporte_clientes():
    data_clientes = []
    try:
        r_clientes = ApiClient.get_usuarios()
        if r_clientes.status_code == 200:
            clientes = r_clientes.json()
            # Simulate some stats for the PDF like the template does
            for i, c in enumerate(clientes[:15]):
                data_clientes.append([
                    c["nombre"],
                    c.get("email", "N/A"),
                    f"{10 - i} pedidos",
                    f"${(10 - i) * 1200:,.2f}"
                ])
    except Exception as e:
        print(f"Error PDF Clientes: {e}")

    headers = ["Nombre del Cliente", "Correo Electrónico", "Total Pedidos", "Monto Acumulado"]
    widths = [60, 60, 35, 35]
    
    pdf_output = generate_report("Reporte de Desempeño de Clientes", headers, data_clientes, widths)
    
    response = make_response(pdf_output)
    response.headers['Content-Type'] = 'application/pdf'
    response.headers['Content-Disposition'] = 'attachment; filename=reporte_clientes.pdf'
    return response
