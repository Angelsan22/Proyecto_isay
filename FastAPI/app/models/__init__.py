# Re-exporta todos los modelos para mantener compatibilidad con el resto del sistema
from app.models.admin import Admin
from app.models.usuario import Usuario
from app.models.pedido import Pedido, DetallePedido
from app.models.producto import Producto

__all__ = ["Admin", "Usuario", "Pedido", "DetallePedido", "Producto"]
