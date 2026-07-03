import { useEffect, useState } from "react";
import api from "../../api/api";

const empty = { category_id: "", name: "", description: "", price: "", stock: "" };

export default function AdminProducts() {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [form, setForm] = useState(empty);
  const [imageFile, setImageFile] = useState(null);

  function loadProducts() {
    api.get("/products").then((res) => setProducts(res.data.data));
  }

  useEffect(() => {
    loadProducts();
    api.get("/categories").then((res) => setCategories(res.data));
  }, []);

  async function handleSubmit(e) {
    e.preventDefault();

    const formData = new FormData();
    Object.entries(form).forEach(([key, value]) => formData.append(key, value));
    if (imageFile) {
      formData.append("image", imageFile);
    }

    await api.post("/products", formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });

    setForm(empty);
    setImageFile(null);
    loadProducts();
  }

  async function handleDelete(id) {
  try {
    await api.delete(`/products/${id}`);
    loadProducts();
  } catch (err) {
    if (err.response?.status === 409) {
      alert(err.response.data.message);
    } else {
      alert("Une erreur est survenue lors de la suppression.");
    }
  }
}
  return (
    <div className="admin-products">
      <h2>Gestion des produits</h2>

      <form onSubmit={handleSubmit} className="admin-form">
        <select
          value={form.category_id}
          onChange={(e) => setForm({ ...form, category_id: e.target.value })}
          required
        >
          <option value="">Catégorie...</option>
          {categories.map((c) => (
            <option key={c.id} value={c.id}>{c.name}</option>
          ))}
        </select>
        <input
          placeholder="Nom"
          value={form.name}
          onChange={(e) => setForm({ ...form, name: e.target.value })}
          required
        />
        <input
          placeholder="Prix"
          type="number"
          step="0.01"
          value={form.price}
          onChange={(e) => setForm({ ...form, price: e.target.value })}
          required
        />
        <input
          placeholder="Stock"
          type="number"
          value={form.stock}
          onChange={(e) => setForm({ ...form, stock: e.target.value })}
          required
        />
        <input
          type="file"
          accept="image/*"
          onChange={(e) => setImageFile(e.target.files[0])}
        />
        <button type="submit">Ajouter le produit</button>
      </form>

      <table>
        <thead>
          <tr><th></th><th>Nom</th><th>Prix</th><th>Stock</th><th></th></tr>
        </thead>
        <tbody>
          {products.map((p) => (
            <tr key={p.id}>
              <td>
                {p.image_full_url && (
                  <img src={p.image_full_url} alt={p.name} style={{ width: 50, height: 50, objectFit: "cover", borderRadius: 6 }} />
                )}
              </td>
              <td>{p.name}</td>
              <td>{p.price} €</td>
              <td>{p.stock}</td>
              <td><button onClick={() => handleDelete(p.id)}>Supprimer</button></td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}