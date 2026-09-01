import { useRouter } from 'next/router';

export default function Post() {
  const router = useRouter();
  const { postId } = router.query;

  return (
    <div>
      <h1>Artigo do Blog</h1>
      <p>ID do post: {postId}</p>
      <article>
        <p>Conteúdo do artigo...</p>
      </article>
    </div>
  );
}
