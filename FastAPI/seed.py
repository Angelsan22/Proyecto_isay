from app.database import SessionLocal, engine
from app import models
from app.main import get_password_hash

models.Base.metadata.create_all(bind=engine)
db = SessionLocal()

admin_email = "admin@macuin.com"
existing_admin = db.query(models.Admin).filter(models.Admin.email == admin_email).first()

if not existing_admin:
    print("Seeding inicial en la API de FastAPI...")
    new_admin = models.Admin(
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
