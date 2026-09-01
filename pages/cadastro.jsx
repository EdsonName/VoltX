export default function Cadastro() {
  return (
    <div>
      <h1>Cadastro</h1>
      <form>
        <input type="text" placeholder="Nome completo" />
        <input type="email" placeholder="Email" />
        <input type="password" placeholder="Senha" />
        <input type="password" placeholder="Confirmar senha" />
        <button type="submit">Cadastrar</button>
      </form>
    </div>
  );
}
