import { Link } from "react-router-dom";

export default function ProductCard({ product }) {
  return (
    <div className="product-card">
<img src={product.image_full_url || "https://placehold.co/300x200"} alt={product.name} />
      <h3>{product.name}</h3>
      <p className="price">{Number(product.price).toFixed(2)} €</p>
      <Link to={`/products/${product.id}`}>Voir le produit</Link>
    </div>
  );
}