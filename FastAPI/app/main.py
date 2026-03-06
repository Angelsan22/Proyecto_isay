from fastapi import FastAPI

app = FastAPI(
    title="Maccuin",
    description="Sanchez Linares Jose Angel, " \
    "Licea Gonzalez Eduardo Daniel, " \
    "Narciso Bernardino Erick, " \
    "Velazquez Velazquez Antonio Abraham",
    version="1.0"
)

@app.get("/")
async def bienvenida():
    return {"mensaje": "API funcionando"}

@app.get("/usuarios")
async def obtener_usuarios():
    return [
        {"id": 1, "nombre": "Angel"},
        {"id": 2, "nombre": "Juan"},
        {"id": 3, "nombre": "Maria"}
    ]

@app.get("/productos")
async def obtener_productos():
    return [
        {"id": 1, "producto": "Disco de Freno Cerámico",          "sku": "ABC12345", "precio": 120.50, "categoria": "Suspensión",   "marca": "Brembo"},
        {"id": 2, "producto": "Bujía de Iridio Alto Rendimiento", "sku": "ABC12346", "precio":  45.00, "categoria": "Motor",        "marca": "NGK"},
        {"id": 3, "producto": "Filtro de Aire Deportivo",         "sku": "ABC12347", "precio":  35.00, "categoria": "Motor",        "marca": "K&N"},
        {"id": 4, "producto": "Faro Denalter LED",                "sku": "ABC12348", "precio": 120.50, "categoria": "Electricidad", "marca": "Philips"},
        {"id": 5, "producto": "Batería de Gel",                   "sku": "ABC12350", "precio": 200.00, "categoria": "Electricidad", "marca": "Bosch"},
    ]