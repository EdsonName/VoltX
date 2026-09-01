export default function Admin() {
  return (
    <div>
      <h1>Painel Administrativo</h1>
      <nav>
        <ul>
          <li><a href="/admin/servicos">Gerenciar Serviços</a></li>
          <li><a href="/admin/agendamentos">Gerenciar Agendamentos</a></li>
          <li><a href="/admin/orcamentos">Gerenciar Orçamentos</a></li>
          <li><a href="/admin/postagens">Gerenciar Postagens</a></li>
        </ul>
      </nav>
    </div>
  );
}
