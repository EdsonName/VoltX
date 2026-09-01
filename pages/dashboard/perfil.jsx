export default function Perfil() {
  return (
    <div>
      <h1>Perfil do Cliente</h1>
      <form>
        <input type="text" placeholder="Nome completo" />
        <input type="email" placeholder="Email" />
        <input type="tel" placeholder="Telefone" />
        <input type="text" placeholder="Endereço" />
        <button type="submit">Atualizar Perfil</button>
      </form>
    </div>
  );
}
