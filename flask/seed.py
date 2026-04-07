from app import create_app, db
from app.models import Usuario

app = create_app()

with app.app_context():
    if Usuario.query.count() == 0:
        print("Añadiendo usuarios dummy...")
        nombres = [
            ("Diana", "dianalizsl@gmail.com"),
            ("Ivan Isay", "ivan@gmail.com"),
            ("Angel", "angel@gmail.com"),
            ("Maria Palma", "maria.palma@gmail.com")
        ]
        
        for n, c in nombres:
            u = Usuario(nombre=n, correo=c)
            db.session.add(u)
            
        db.session.commit()
        print("Usuarios dummy creados con éxito.")
    else:
        print("Ya existen usuarios.")
