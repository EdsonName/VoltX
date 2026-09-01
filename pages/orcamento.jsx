export default function Orcamento() {
  return (
    <div>
      <h1>Solicitação de Orçamento</h1>
      <form>
        <input type="text" placeholder="Nome" />
        <input type="email" placeholder="Email" />
        <textarea placeholder="Descreva seu projeto..."></textarea>
        <button type="submit">Solicitar Orçamento</button>
      </form>
    </div>
  );
}
