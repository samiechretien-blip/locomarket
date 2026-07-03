import { BrowserRouter, Routes, Route, Link } from "react-router-dom";
import { AuthProvider, useAuth } from "./context/AuthContext";
import { CartProvider, useCart } from "./context/CartContext";
import ProductDetail from "./pages/ProductDetail";
import ProductList from "./pages/ProductList";
import Cart from "./pages/Cart";
import Login from "./pages/Login";
import Register from "./pages/Register";
import AdminProducts from "./pages/admin/AdminProducts";
import ProtectedRoute from "./components/ProtectedRoute";
import MyOrders from "./pages/MyOrders";
function Header() {
  const { user, logout } = useAuth();
  const { items } = useCart();

  return (
    <header>
      <Link to="/">LocoMarket</Link>
      <Link to="/cart" style={{ marginLeft: 10 }}>Panier ({items.length})</Link>
      {user ? (
        <>
          <Link to="/orders" style={{ marginLeft: 10 }}>Mes commandes</Link>
          {user.role === "admin" && (
            <Link to="/admin/products" style={{ marginLeft: 10 }}>Administration</Link>
          )}
          <button onClick={logout} style={{ marginLeft: 10 }}>Déconnexion</button>
        </>
      ) : (
        <Link to="/login" style={{ marginLeft: 10 }}>Connexion</Link>
      )}
    </header>
  );
}

export default function App() {
  return (
    <AuthProvider>
      <CartProvider>
        <BrowserRouter>
          <Header />
          <main>
            <Routes>
              <Route path="/" element={<ProductList />} />
              <Route path="/products/:id" element={<ProductDetail />} />
              <Route path="/cart" element={<Cart />} />
              <Route path="/login" element={<Login />} />
              <Route path="/register" element={<Register />} />
              <Route
  path="/orders"
  element={
    <ProtectedRoute>
      <MyOrders />
    </ProtectedRoute>
  }
/>
              <Route
  path="/admin/products"
  element={
    <ProtectedRoute adminOnly>
      <AdminProducts />
    </ProtectedRoute>
  }
/>
            </Routes>
          </main>
        </BrowserRouter>
      </CartProvider>
    </AuthProvider>
  );
}