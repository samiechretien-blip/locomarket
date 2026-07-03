import { useEffect, useState } from "react";
import api from "../api/api";
import ProductCard from "../components/ProductCard";

export default function ProductList() {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [category, setCategory] = useState("");
  const [search, setSearch] = useState("");

  useEffect(() => {
    api.get("/categories").then((res) => setCategories(res.data));
  }, []);

  useEffect(() => {
    api
      .get("/products", { params: { category: category || undefined, search: search || undefined } })
      .then((res) => setProducts(res.data.data)); // pagination Laravel -> .data
  }, [category, search]);

  return (
    <div className="catalogue">
      <aside>
        <input
          placeholder="Rechercher un produit..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
        />
        <ul>
          <li onClick={() => setCategory("")}>Toutes les catégories</li>
          {categories.map((c) => (
            <li key={c.id} onClick={() => setCategory(c.id)}>
              {c.name}
            </li>
          ))}
        </ul>
      </aside>
      <section className="product-grid">
        {products.map((p) => (
          <ProductCard key={p.id} product={p} />
        ))}
      </section>
    </div>
  );
}