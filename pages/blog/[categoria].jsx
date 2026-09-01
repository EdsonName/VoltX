import { useRouter } from 'next/router';

export default function BlogPorCategoria() {
  const router = useRouter();
  const { categoria } = router.query;

  return (
    <div>
      <h1>Blog - {categoria}</h1>
      <p>Posts da categoria: {categoria}</p>
    </div>
  );
}
