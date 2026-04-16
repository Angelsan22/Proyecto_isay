from fastapi import FastAPI
from app import models, database, schemas
from app.database import engine
from app.routers import administradores, usuarios, productos, pedidos, estadisticas, reportes

models.Base.metadata.create_all(bind=engine)

app = FastAPI(
    title="Maccuin API",
    description="Backend centralizado y modularizado",
    version="1.1"
)

app.include_router(administradores.router)
app.include_router(usuarios.router)
app.include_router(productos.router)
app.include_router(pedidos.router)
app.include_router(estadisticas.router)
app.include_router(reportes.router)

@app.get("/catalogo_legacy", tags=["Catálogo"])
def read_catalogo_legacy():
    return {"message": "Catálogo legacy activo"}

@app.get("/")
def read_root():
    return {"message": "Macuin API - Sistema Centralizado Activo", "version": "1.1"}