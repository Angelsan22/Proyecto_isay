from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker
from app.database import Base, SQLALCHEMY_DATABASE_URL
from app.models import Producto
from datetime import datetime

engine = create_engine(SQLALCHEMY_DATABASE_URL)
Base.metadata.create_all(bind=engine)
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
db = SessionLocal()

def seed():
    productos = [
        {"nombre": "Pastillas de Freno Delanteras", "categoria": "Frenos", "precio": 450, "stock_actual": 3, "stock_minimo": 10, "descripcion": "Pastillas cerámicas de alta resistencia"},
        {"nombre": "Amortiguador de Gas (Par)", "categoria": "Suspensión", "precio": 1200, "stock_actual": 15, "stock_minimo": 5, "descripcion": "Amortiguadores reforzados"},
        {"nombre": "Bujía Iridium (Set 4)", "categoria": "Motor", "precio": 850, "stock_actual": 8, "stock_minimo": 5, "descripcion": "Bujías de alto desempeño"},
        {"nombre": "Filtro de Aceite Sintético", "categoria": "Servicio", "precio": 180, "stock_actual": 25, "stock_minimo": 10, "descripcion": "Filtración premium"},
        {"nombre": "Disco de Freno Ranurado", "categoria": "Frenos", "precio": 950, "stock_actual": 2, "stock_minimo": 4, "descripcion": "Ventilación mejorada"},
        {"nombre": "Batería 12V 750A", "categoria": "Eléctrico", "precio": 2400, "stock_actual": 6, "stock_minimo": 3, "descripcion": "Garantía 48 meses"}
    ]
    
    for p_data in productos:
        prod = db.query(Producto).filter(Producto.nombre == p_data["nombre"]).first()
        if not prod:
            prod = Producto(**p_data)
            db.add(prod)
            print(f"Producto creado: {prod.nombre}")
    
    db.commit()
    print("Seeding de inventario completado.")

if __name__ == "__main__":
    seed()
    db.close()
