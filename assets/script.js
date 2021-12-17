// document.addEventListener('DOMContentLoaded', function (event) {

//   const link_full_review_items = document.querySelectorAll('.link-full-review')
//   const review_posts_full_content_items = document.querySelectorAll('.review-posts-full-content')
//   const close_link_full_review_items = document.querySelectorAll('.close-link-full-review')
//   const review_posts_entry_content_items = document.querySelectorAll('.review-posts-entry-content')


//   // Add show class, show full review, number of links
//   for (let i = 0; i < link_full_review_items.length; i++) {
//     const item = link_full_review_items[i]
//     item.addEventListener('click', () => addShowClass(item, i))
//   }

//   function addShowClass (item, i) {
//     item.classList.toggle('hide')
//     review_posts_entry_content_items[i].classList.toggle('hide')
//     review_posts_full_content_items[i].classList.toggle('show')
//     close_link_full_review_items[i].classList.toggle('show')
//   }

//   // Hide full review
//   for (let n = 0; n < close_link_full_review_items.length; n++) {
//     const item = close_link_full_review_items[n]
//     item.addEventListener('click', () => deleteShowClass(n))
//   }

//   function deleteShowClass (n) {
//     close_link_full_review_items[n].classList.toggle('show')
//     link_full_review_items[n].classList.toggle('hide')
//     review_posts_full_content_items[n].classList.toggle('show')
//     review_posts_entry_content_items[n].classList.toggle('hide')
//   }

// })
jQuery( document ).ready(function($) {
	$('#share').on('click', function(){
		 $.fancybox.open( $('.share'), {

		  });
	});

	$('.base64img').each(function(){
		$(this).attr('src', 'data:' + $(this).attr('src'));
	});
});