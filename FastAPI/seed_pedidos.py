from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker
from app.data.database import Base
from app.core.config import settings
from app.models.usuario import Usuario
from app.models.pedido import Pedido
from datetime import datetime

engine = create_engine(settings.DATABASE_URL)
Base.metadata.create_all(bind=engine)
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
db = SessionLocal()

def seed():
    # 1. Asegurar que existan clientes
    clientes = [
        {"nombre": "Refaccionaria Sánchez", "correo": "sanchez@ejemplo.com"},
        {"nombre": "Taller El Rayo", "correo": "rayo@ejemplo.com"},
        {"nombre": "Autopartes Express", "correo": "express@ejemplo.com"}
    ]
    
    for c_data in clientes:
        user = db.query(Usuario).filter(Usuario.correo == c_data["correo"]).first()
        if not user:
            user = Usuario(**c_data)
            db.add(user)
            db.commit()
            db.refresh(user)
            print(f"Cliente creado: {user.nombre}")

    # 2. Crear Pedidos
    all_users = db.query(Usuario).all()
    if len(all_users) >= 3:
        pedidos_data = [
            {"cliente_id": all_users[0].id, "total": 4250, "estatus": "En Proceso"},
            {"cliente_id": all_users[1].id, "total": 1890, "estatus": "Enviado"},
            {"cliente_id": all_users[2].id, "total": 12400, "estatus": "Entregado"},
            {"cliente_id": all_users[0].id, "total": 850, "estatus": "Entregado"}
        ]
        
        for p_data in pedidos_data:
            pedido = Pedido(**p_data)
            db.add(pedido)
            print(f"Pedido creado para {all_users[0].nombre}")  # Simplificado para log
            
        db.commit()
        print("Seeding de pedidos completado.")
    else:
        print("No hay suficientes usuarios para seedear pedidos.")

if __name__ == "__main__":
    seed()
    db.close()
