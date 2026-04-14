from app.data.database import SessionLocal, engine, Base
from app.models.admin import Admin
from app.security.hashing import get_password_hash

Base.metadata.create_all(bind=engine)
db = SessionLocal()

admin_email = "admin@macuin.com"
existing_admin = db.query(Admin).filter(Admin.email == admin_email).first()

if not existing_admin:
    print("Seeding inicial en la API de FastAPI...")
    new_admin = Admin(
        nombre="Administrador Principal",
        email=admin_email,
        password_hash=get_password_hash("admin123")
    )
    db.add(new_admin)
    db.commit()
    print("Seed ok")
else:
    print("Admin ya existe")
db.close()
