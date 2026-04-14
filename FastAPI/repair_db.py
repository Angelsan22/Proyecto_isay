from sqlalchemy import create_engine, MetaData, Table
from sqlalchemy.orm import sessionmaker
from app.data.database import Base
from app.core.config import settings
from app.models.producto import Producto
from datetime import datetime

engine = create_engine(settings.DATABASE_URL)
metadata = MetaData()

def repair_and_seed():
    # 1. Intentar borrar la tabla si existe para asegurar esquema limpio
    try:
        print("Intentando limpiar tabla de productos anterior...")
        table = Table('productos', metadata, autoload_with=engine)
        table.drop(engine, checkfirst=True)
        print("Tabla 'productos' eliminada.")
    except Exception as e:
        print(f"Nota: No se pudo eliminar la tabla (quizás no existía): {e}")

    # 2. Crear tablas de nuevo según el modelo actual
    Base.metadata.create_all(bind=engine)
    print("Tablas recreadas con el esquema actual.")

    # 3. Seed de nuevo
    SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
    db = SessionLocal()

    productos = [
        {"nombre": "Pastillas de Freno Delanteras", "categoria": "Frenos", "precio": 450, "stock_actual": 3, "stock_minimo": 10},
        {"nombre": "Amortiguador de Gas (Par)", "categoria": "Suspensiva", "precio": 1200, "stock_actual": 15, "stock_minimo": 5},
        {"nombre": "Bujía Iridium (Set 4)", "categoria": "Motor", "precio": 850, "stock_actual": 8, "stock_minimo": 5},
        {"nombre": "Filtro de Aceite Sintético", "categoria": "Servicio", "precio": 180, "stock_actual": 25, "stock_minimo": 10},
        {"nombre": "Disco de Freno Ranurado", "categoria": "Frenos", "precio": 950, "stock_actual": 2, "stock_minimo": 4},
        {"nombre": "Batería 12V 750A", "categoria": "Eléctrico", "precio": 2400, "stock_actual": 6, "stock_minimo": 3}
    ]

    for p_data in productos:
        # Asegurar tipos de datos antes de insertar
        prod = Producto(
            nombre=str(p_data["nombre"]),
            categoria=str(p_data["categoria"]),
            precio=int(p_data["precio"]),
            stock_actual=int(p_data["stock_actual"]),
            stock_minimo=int(p_data["stock_minimo"]),
            descripcion="Autoparte de reemplazo premium"
        )
        db.add(prod)
        print(f"Sembrando: {prod.nombre} (Stock: {prod.stock_actual})")
    
    db.commit()
    db.close()
    print("Reparación y seeding completados con éxito.")

if __name__ == "__main__":
    repair_and_seed()
