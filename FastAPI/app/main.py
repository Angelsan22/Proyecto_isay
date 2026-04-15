from fastapi import FastAPI, Depends, HTTPException, status
from sqlalchemy.orm import Session
from typing import List, Optional
from werkzeug.security import generate_password_hash, check_password_hash

from app import models, schemas, database
from app.database import engine

# Crear tablas
models.Base.metadata.create_all(bind=engine)

app = FastAPI(
    title="Maccuin API",
    description="Backend centralizado",
    version="1.0"
)

# Password hashing
def verify_password(plain_password, hashed_password):
    return check_password_hash(hashed_password, plain_password)

def get_password_hash(password):
    return generate_password_hash(password)

# --- RUTAS DE ADMINISTRADORES ---

@app.get("/admins/", response_model=List[schemas.AdminResponse], tags=["Administradores"])
def read_admins(skip: int = 0, limit: int = 100, db: Session = Depends(database.get_db)):
    admins = db.query(models.Admin).offset(skip).limit(limit).all()
    # Populate creador string info if needed, but returning just models is fine
    return admins

@app.post("/admins/", response_model=schemas.AdminResponse, tags=["Administradores"])
def create_admin(admin: schemas.AdminCreate, db: Session = Depends(database.get_db)):
    db_admin = db.query(models.Admin).filter(models.Admin.email == admin.email).first()
    if db_admin:
        raise HTTPException(status_code=400, detail="Email ya registrado")
    
    hashed_password = get_password_hash(admin.password)
    db_admin = models.Admin(
        nombre=admin.nombre,
        email=admin.email,
        password_hash=hashed_password,
        creador_id=admin.creador_id
    )
    db.add(db_admin)
    db.commit()
    db.refresh(db_admin)
    return db_admin

@app.post("/admins/login", tags=["Autenticación"])
def login_admin(admin: schemas.AdminLogin, db: Session = Depends(database.get_db)):
    db_admin = db.query(models.Admin).filter(models.Admin.email == admin.email).first()
    if not db_admin or not verify_password(admin.password, db_admin.password_hash):
        raise HTTPException(status_code=401, detail="Credenciales incorrectas")
    
    return {"message": "Login exitoso", "admin_id": db_admin.id, "nombre": db_admin.nombre, "email": db_admin.email}

@app.delete("/admins/{admin_id}", tags=["Administradores"])
def delete_admin(admin_id: int, db: Session = Depends(database.get_db)):
    db_admin = db.query(models.Admin).filter(models.Admin.id == admin_id).first()
    if not db_admin:
        raise HTTPException(status_code=404, detail="Administrador no encontrado")
    db.delete(db_admin)
    db.commit()
    return {"message": "Admin eliminado"}

# --- RUTAS DE USUARIOS REGULARES ---

@app.get("/usuarios/", response_model=List[schemas.UsuarioResponse], tags=["Usuarios Clientes"])
def read_usuarios(skip: int = 0, limit: int = 100, db: Session = Depends(database.get_db)):
    usuarios = db.query(models.Usuario).offset(skip).limit(limit).all()
    return usuarios

@app.post("/usuarios/", response_model=schemas.UsuarioResponse, tags=["Usuarios Clientes"])
def create_usuario(usuario: schemas.UsuarioCreate, db: Session = Depends(database.get_db)):
    db_usuario = db.query(models.Usuario).filter(models.Usuario.correo == usuario.correo).first()
    if db_usuario:
        raise HTTPException(status_code=400, detail="Correo ya registrado")
    
    db_usuario = models.Usuario(nombre=usuario.nombre, correo=usuario.correo)
    db.add(db_usuario)
    db.commit()
    db.refresh(db_usuario)
    return db_usuario

@app.put("/usuarios/{usuario_id}", response_model=schemas.UsuarioResponse, tags=["Usuarios Clientes"])
def update_usuario(usuario_id: int, usuario: schemas.UsuarioCreate, db: Session = Depends(database.get_db)):
    db_usuario = db.query(models.Usuario).filter(models.Usuario.id == usuario_id).first()
    if not db_usuario:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")
    
    db_usuario.nombre = usuario.nombre
    db_usuario.correo = usuario.correo
    db.commit()
    db.refresh(db_usuario)
    return db_usuario

@app.delete("/usuarios/{usuario_id}", tags=["Usuarios Clientes"])
def delete_usuario(usuario_id: int, db: Session = Depends(database.get_db)):
    db_usuario = db.query(models.Usuario).filter(models.Usuario.id == usuario_id).first()
    if not db_usuario:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")
    db.delete(db_usuario)
    db.commit()
    return {"message": "Usuario eliminado"}

# --- RUTAS DE PEDIDOS ---

@app.get("/pedidos/", response_model=List[schemas.PedidoResponse], tags=["Gestión de Pedidos"])
def read_pedidos(cliente: Optional[str] = None, estatus: Optional[str] = None, db: Session = Depends(database.get_db)):
    query = db.query(models.Pedido)
    
    if cliente:
        # Búsqueda por nombre de cliente a través de la relación
        query = query.join(models.Usuario).filter(models.Usuario.nombre.ilike(f"%{cliente}%"))
    
    if estatus and estatus != "Todos los pedidos":
        query = query.filter(models.Pedido.estatus == estatus)
        
    return query.all()

@app.post("/pedidos/", response_model=schemas.PedidoResponse, tags=["Gestión de Pedidos"])
def create_pedido(pedido: schemas.PedidoCreate, db: Session = Depends(database.get_db)):
    db_pedido = models.Pedido(**pedido.dict())
    db.add(db_pedido)
    db.commit()
    db.refresh(db_pedido)
    return db_pedido

# --- RUTAS DE INVENTARIO (PRODUCTOS) ---

@app.get("/productos/", response_model=List[schemas.ProductoResponse], tags=["Gestión de Inventario"])
def read_productos(nombre: Optional[str] = None, categoria: Optional[str] = None, db: Session = Depends(database.get_db)):
    query = db.query(models.Producto)
    if nombre:
        query = query.filter(models.Producto.nombre.ilike(f"%{nombre}%"))
    if categoria and categoria != "Todas las categorías":
        query = query.filter(models.Producto.categoria == categoria)
    return query.all()

@app.get("/productos/{producto_id}", response_model=schemas.ProductoResponse, tags=["Gestión de Inventario"])
def read_producto(producto_id: int, db: Session = Depends(database.get_db)):
    db_producto = db.query(models.Producto).filter(models.Producto.id == producto_id).first()
    if not db_producto:
        raise HTTPException(status_code=404, detail="Producto no encontrado")
    return db_producto

@app.post("/productos/", response_model=schemas.ProductoResponse, tags=["Gestión de Inventario"])
def create_producto(producto: schemas.ProductoCreate, db: Session = Depends(database.get_db)):
    db_producto = models.Producto(**producto.dict())
    db.add(db_producto)
    db.commit()
    db.refresh(db_producto)
    return db_producto

@app.put("/productos/{producto_id}", response_model=schemas.ProductoResponse, tags=["Gestión de Inventario"])
def update_producto(producto_id: int, producto: schemas.ProductoCreate, db: Session = Depends(database.get_db)):
    db_producto = db.query(models.Producto).filter(models.Producto.id == producto_id).first()
    if not db_producto:
        raise HTTPException(status_code=404, detail="Producto no encontrado")
    
    for key, value in producto.dict().items():
        setattr(db_producto, key, value)
    
    db.commit()
    db.refresh(db_producto)
    return db_producto

@app.delete("/productos/{producto_id}", tags=["Gestión de Inventario"])
def delete_producto(producto_id: int, db: Session = Depends(database.get_db)):
    db_producto = db.query(models.Producto).filter(models.Producto.id == producto_id).first()
    if not db_producto:
        raise HTTPException(status_code=404, detail="Producto no encontrado")
    db.delete(db_producto)
    db.commit()
    return {"message": "Producto eliminado"}

# --- Endpoint original legacy (redireccionado o mantenido para compatibilidad) ---
@app.get("/productos_legacy", tags=["Catálogo de Productos"])
async def obtener_productos_legacy():
    pass