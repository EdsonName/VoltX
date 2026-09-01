import { useRouter } from 'next/router';

export default function AgendarServico() {
  const router = useRouter();
  const { id } = router.query;

  return (
    <div>
      <h1>Agendar Serviço</h1>
      <p>ID do serviço: {id}</p>
      <form>
        <input type="date" placeholder="Data" />
        <input type="time" placeholder="Hora" />
        <textarea placeholder="Observações..."></textarea>
        <button type="submit">Confirmar Agendamento</button>
      </form>
    </div>
  );
}
