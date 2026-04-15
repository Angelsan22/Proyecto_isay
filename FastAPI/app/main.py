from fastapi import FastAPI
from app.models import Admin, Usuario, Pedido, DetallePedido, Producto
from app.data.database import engine, Base
from app.routers import usuarios, productos, pedidos, administradores, estadisticas, reportes

# Crear tablas en la base de datos
Base.metadata.create_all(bind=engine)

app = FastAPI(
    title="Maccuin API",
    description="Backend centralizado para la gestión de autopartes",
    version="1.0"
)

# Registrar todos los routers
app.include_router(administradores.router)
app.include_router(usuarios.router)
app.include_router(pedidos.router)
app.include_router(productos.router)
app.include_router(estadisticas.router)
app.include_router(reportes.router)

# --- Endpoint legacy (mantenido para compatibilidad) ---
@app.get("/productos_legacy", tags=["Catálogo de Productos"])
async def obtener_productos_legacy():
    pass