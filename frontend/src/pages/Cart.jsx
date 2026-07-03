import { useNavigate } from "react-router-dom";
import api from "../api/api";
import { useCart } from "../context/CartContext";

export default function Cart() {
  const { items, updateQuantity, removeFromCart, clearCart, total } = useCart();
  const navigate = useNavigate();

  async function handleCheckout() {
    const payload = {
      items: items.map((i) => ({ product_id: i.product.id, quantity: i.quantity })),
    };
    await api.post("/orders", payload);
    clearCart();
    navigate("/");
  }

  if (items.length === 0) return <p>Votre panier est vide.</p>;

  return (
    <div className="cart">
      <table>
        <thead>
          <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Sous-total</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {items.map(({ product, quantity }) => (
            <tr key={product.id}>
              <td>{product.name}</td>
              <td>
                <input
                  type="number"
                  min="1"
                  value={quantity}
                  onChange={(e) => updateQuantity(product.id, Number(e.target.value))}
                />
              </td>
              <td>{Number(product.price).toFixed(2)} €</td>
              <td>{(product.price * quantity).toFixed(2)} €</td>
              <td>
                <button onClick={() => removeFromCart(product.id)}>Retirer</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      <p className="total">Total : {total.toFixed(2)} €</p>
      <button onClick={handleCheckout}>Commander</button>
    </div>
  );
}