<!-- BEGIN: main -->

<!-- BEGIN: loop -->
<div class="dicuss-item aw-pq2-list__helpfulness" id="faqcontent{FAQ.faq_id}">
    <div class="ask-votes">
        <p class="ask-votes-number" id="total-likes{FAQ.faq_id}"> {FAQ.likes} </p>
        <span class="aw-pq2-list__helpfulness-progress" style="display:none;"></span> lượt thích
    </div>
    <div class="discuss-content">
        <a href="#/hoi-dap/625">
            <p class="discuss-owner">{FAQ.question}</p>
        </a>
        
 		<!-- BEGIN: subloop -->
		<p class="discuss-ans">{FAQSUB.question}</p>
		<!-- END: subloop -->
        <div class="dis-control">
            <a class="likes" href="javascript:void(0);" data="0" onclick="users_likes( this, '{FAQ.faq_id}', '{PRODUCT_ID}', '{FAQ.token}' )" id="likes{FAQ.faq_id}"> Thích</a>
            <a href="{FAQ.link}">Trả lời</a>
            <a href="{FAQ.link}">Xem tất cả {FAQ.num_ansewer} câu trả lời</a>
        </div>
    </div>
</div>
<!-- END: loop --> 
 
<!-- END: main -->
