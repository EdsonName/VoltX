document.addEventListener('DOMContentLoaded',()=>{
 const link=document.querySelector('#link_url'),form=document.querySelector('.ad-create-form'),file=document.querySelector('#foto');
 if(link){link.type='text';link.placeholder='narrativas.site';const hint=document.createElement('small');hint.textContent='Informe somente o domínio ou cole a URL completa.';link.after(hint)}
 if(!form)return;
 const field=document.createElement('div');field.className='form-group exposure-field';field.innerHTML='<label for="duracao_segundos">Tempo de exposição</label><div><input id="duracao_segundos" name="duracao_segundos" type="range" min="5" max="20" value="10" step="1"><output>10 segundos</output></div><small class="media-rule"></small>';link.closest('.form-group').after(field);
 const range=field.querySelector('input'),output=field.querySelector('output'),rule=field.querySelector('.media-rule');
 if(file){file.name='midias[]';file.multiple=true;file.accept='image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm';file.closest('.form-group').querySelector('label').textContent='Fotos, GIFs ou vídeo';}
 function maxFiles(){const seconds=Number(range.value);return seconds<10?1:seconds===10?3:seconds<15?5:6}
 function refresh(){const seconds=Number(range.value),max=maxFiles();output.textContent=seconds+' segundos';rule.textContent=seconds>=15?`Até ${max} fotos/GIFs ou 1 vídeo de no máximo ${seconds}s.`:`Até ${max} ${max===1?'foto ou GIF':'fotos ou GIFs'}. Vídeo disponível a partir de 15s.`;}
 range.addEventListener('input',refresh);refresh();
 file?.addEventListener('change',async()=>{const files=[...file.files],seconds=Number(range.value),videos=files.filter(item=>item.type.startsWith('video/'));if(files.length>maxFiles()){alert(`Este tempo permite no máximo ${maxFiles()} mídias.`);file.value='';return}if(videos.length&&(seconds<15||files.length!==1)){alert('O vídeo deve ser o único arquivo e exige anúncio de 15 a 20 segundos.');file.value='';return}if(videos.length){const video=document.createElement('video');video.preload='metadata';video.src=URL.createObjectURL(videos[0]);await new Promise(resolve=>{video.onloadedmetadata=resolve;video.onerror=resolve});URL.revokeObjectURL(video.src);if(Number.isFinite(video.duration)&&video.duration>seconds+.25){alert(`O vídeo tem ${Math.ceil(video.duration)}s e ultrapassa os ${seconds}s do anúncio.`);file.value='';}}});
});
