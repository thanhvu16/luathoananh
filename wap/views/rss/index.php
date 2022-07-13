<?php
use common\components\Utility;
?>

<section class="body-content">
    <div class="body-white text-center">
        <div class="container">
            <div class="row">
				<div class="grid1120 box_fff pad_10 clearfix">
					<br><strong class="kn-rss">Khái niệm RSS</strong>
					<p class="des-rss">RSS ( viết tắt từ Really Simple Syndication ) là một tiêu chuẩn định dạng tài liệu dựa trên XML
						nhằm giúp người sử dụng dễ dàng cập nhật và tra cứu thông tin một cách nhanh chóng và thuận tiện nhất bằng cách
						tóm lược thông tin vào trong một đoạn dữ liệu ngắn gọn, hợp chuẩn.</p>
					<p class="des-rss">Dữ liệu này được các chương trình đọc tin chuyên biệt ( gọi là News reader) phân tích và hiển thị
						trên máy tính của người sử dụng. Trên trình đọc tin này, người sử dụng có thể thấy những tin chính mới nhất,
						tiêu đề, tóm tắt và cả đường link để xem toàn bộ tin.</p><strong class="kn-rss">Kênh do Luật Hoàng Anh cung
						cấp</strong>
					<div class="wrap-list-rss">
						<ul class="list-rss">
							<?php foreach($menu as $m) { ?>
								<li><a href="/rss/<?php echo Utility::rewrite($m['title']) ?>.rss" title="<?php echo $m['title'] ?>"><?php echo $m['title'] ?></a><span class="icon-rss">RSS<svg class="ic ic-rss"><use xlink:href="#Rss"><svg id="Rss" viewBox="0 0 32 32">
		<path d="M10 26c0 1.4-0.8 2.7-2 3.5-1.2 0.7-2.8 0.7-4 0s-2-2-2-3.5c0-1.5 0.8-2.7 2-3.5 1.2-0.7 2.8-0.7 4 0s2 2 2 3.5z"></path>
		<path d="M30 30h-5.4c0-12.4-10.2-22.6-22.6-22.6v-5.4c15.4 0 28 12.6 28 28z"></path>
		<path d="M20.6 30h-5.2c0-7.4-6-13.4-13.4-13.4v-5.2c10.2 0 18.6 8.4 18.6 18.6z"></path>
		</svg></use></svg></span></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>


<style>
    .wrap-list-rss {
        display: block;
    }
    .title-rss{
        font-size: 24px;
        line-height: 160%;
        font-weight: 700;
        margin-bottom: 15px;
    }
    .kn-rss{
        font-size: 20px;
        line-height: 160%;
        margin-bottom: 15px;
        display: inline-block;
    }
    .des-rss{
		text-align: left;
        font-size: 18px;
        line-height: 160%;
        margin-bottom: 20px;
    }
    ..wrap-list-rss {
        display: -webkit-flex;
        display: -moz-flex;
        display: -ms-flex;
        display: -o-flex;
        display: flex;
    }
    .wrap-list-rss .list-rss li {
        border-bottom: 1px solid #E5E5E5;
        position: relative;
        display: inline-block;
        width: calc(50% - 20px);
        margin: 0 5px;
        box-sizing: border-box;
    }
    .wrap-list-rss .list-rss:first-child {
        margin-right: 15px;
    }
    .wrap-list-rss .list-rss {
        width: 100%;
        color: #4F4F4F;
        font-size: 18px;
        line-height: 1.1666666667;
        margin-bottom: 15px;
    }
    .wrap-list-rss .list-rss a {
        padding: 15px 0;
        display: inline-block;
        width: calc(100% - 70px);
    }
    .wrap-list-rss .list-rss .icon-rss {
        font-size: 13px;
        line-height: 1.1538461538;
        color: #222;
        margin-top: 3px;
        line-height: 20px;
        transition: all .4s ease;
    }
    .wrap-list-rss .list-rss .icon-rss .ic {
        background: #EE802F;
        fill: #fff;
        padding: 2px;
        width: 20px;
        height: 20px;
        margin-top: -3px;
        margin-left: 10px;
        border-radius: 1px;
    }
    .ic {
        width: 16px;
        height: 16px;
        fill: #757575;
        display: inline-block;
        vertical-align: middle;
    }
</style>