import { useRouter } from 'next/router';

export default function ServicoDetalhes() {
  const router = useRouter();
  const { id } = router.query;

  return (
    <div>
      <h1>Detalhes do Serviço</h1>
      <p>ID do serviço: {id}</p>
      <p>Informações detalhadas do serviço...</p>
    </div>
  );
}
