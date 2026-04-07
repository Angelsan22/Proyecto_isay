from app import create_app, db
from app.models import Admin

app = create_app()

with app.app_context():
    db.create_all()
    
    # Check if admin exists
    admin_email = "admin@macuin.com"
    admin = Admin.query.filter_by(email=admin_email).first()
    
    if not admin:
        print(f"Creando cuenta de administrador inicial: {admin_email}")
        nuevo_admin = Admin(nombre="Administrador Principal", email=admin_email)
        nuevo_admin.set_password("admin123")
        # El administrador principal no tiene creador_id, o podemos dejarlo None
        db.session.add(nuevo_admin)
        db.session.commit()
        print("Administrador creado con éxito.")
    else:
        print("El administrador inicial ya existe en la base de datos.")
