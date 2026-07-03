import { useEffect, useState } from "react";
import api from "../api/api";

export default function MyOrders() {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api.get("/orders")
      .then((res) => setOrders(res.data))
      .finally(() => setLoading(false));
  }, []);

  if (loading) return <p>Chargement...</p>;
  if (orders.length === 0) return <p>Vous n'avez pas encore de commande.</p>;

  return (
    <div className="my-orders">
      <h2>Mes commandes</h2>
      {orders.map((order) => (
        <div key={order.id} className="order-card">
          <div className="order-header">
            <span>Commande #{order.id}</span>
            <span className={`status status-${order.status}`}>{order.status}</span>
            <span>{new Date(order.created_at).toLocaleDateString()}</span>
          </div>
          <table>
            <tbody>
              {order.items.map((item) => (
                <tr key={item.id}>
                  <td>{item.product?.name ?? "Produit supprimé"}</td>
                  <td>x{item.quantity}</td>
                  <td>{Number(item.unit_price).toFixed(2)} €</td>
                </tr>
              ))}
            </tbody>
          </table>
          <p className="order-total">Total : {Number(order.total).toFixed(2)} €</p>
        </div>
      ))}
    </div>
  );
}