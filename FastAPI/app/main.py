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
        {"id": 1, "producto": "Laptop"},
        {"id": 2, "producto": "Mouse"},
        {"id": 3, "producto": "Teclado"}
    ]