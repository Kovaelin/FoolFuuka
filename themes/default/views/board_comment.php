<?php
if (!defined('DOCROOT'))
	exit('No direct script access allowed');

$num =  $p->num . ( $p->subnum ? '_' . $p->subnum : '' );
$quote_mode = (isset($is_last50) && $is_last50) ? 'last50' : 'thread';
?>

<article class="post doc_id_<?= $p->doc_id ?>
	<?php if ($p->subnum > 0) : ?> post_ghost<?php endif; ?>
	<?php if ($p->thread_num == $p->num) : ?> post_is_op<?php endif; ?>
	<?php if (isset($p->report_status) && !is_null($p->report_status)) : ?> reported<?php endif; ?>
	<?php if (!is_null($p->media)) : ?> has_image clearfix<?php endif; ?>" id="<?= $num ?>">

	<?php if (!is_null($p->media)) : ?>
	<div class="post_file">

		<span class="post_file_controls">
		<?php if ($p->media->media_status != 'banned') : ?>
			<?php if (!$p->board->hide_thumbnails || Auth::has_access('maccess.mod')) : ?>
			<a href="<?= Uri::create('@radix/' . $p->board->shortname . '/search/image/' . $p->media->safe_media_hash) ?>" class="btnr parent"><?= __('View Same') ?></a><a
				href="http://google.com/searchbyimage?image_url=<?= $p->media->thumb_link ?>" target="_blank" class="btnr parent">Google</a><a
				href="http://iqdb.org/?url=<?= $p->media->thumb_link ?>" target="_blank" class="btnr parent">iqdb</a><a
				href="http://saucenao.com/search.php?url=<?= $p->media->thumb_link ?>" target="_blank" class="btnr parent">SauceNAO</a>
			<?php endif; ?>
		<?php endif ?>
		</span>

		<?php if (mb_strlen($p->media->media_filename_processed) > 38) : ?>
			<span class="post_file_filename" rel="tooltip" title="<?= htmlspecialchars($p->media->media_filename) ?>">
				<?= mb_substr($p->media->media_filename_processed, 0, 32) . ' (...)' . mb_substr($p->media->media_filename_processed, mb_strrpos($p->media->media_filename_processed, '.')) . ', ' ?>
			</span>
		<?php else: ?>
			<?= $p->media->media_filename_processed . ', ' ?>
		<?php endif; ?>

		<span class="post_file_metadata">
			<?= \Num::format_bytes($p->media->media_size, 0) . ', ' . $p->media->media_w . 'x' . $p->media->media_h ?>
		</span>
	</div>

	<div class="thread_image_box">
		<?php if ($p->media->media_status == 'banned') : ?>
			<img src="<?= Uri::base() . $this->fallback_asset('images/banned-image.png') ?>" width="150" height="150" />
		<?php elseif ($p->media->thumb_link === false): ?>
			<a href="<?= ($p->media->media_link) ? $p->media->media_link : $p->media->remote_media_link ?>" target="_blank" rel="noreferrer" class="thread_image_link">
				<img src="<?= Uri::base() . $this->fallback_asset('images/missing-image.jpg') ?>" width="150" height="150" />
			</a>
		<?php else: ?>
			<a href="<?= ($p->media->media_link) ? $p->media->media_link : $p->media->remote_media_link ?>" target="_blank" rel="noreferrer" class="thread_image_link">
				<?php if(!Auth::has_access('maccess.mod') && !$p->board->transparent_spoiler && $p->spoiler) :?>
				<div class="spoiler_box"><span class="spoiler_box_text"><?= __('Spoiler') ?><span class="spoiler_box_text_help"><?= __('Click to view') ?></span></div>
				<?php elseif (isset($modifiers['lazyload']) && $modifiers['lazyload'] == TRUE) : ?>
				<img src="<?= Uri::create('content/themes/default/images/transparent_pixel.png') ?>" data-original="<?= $p->media->thumb_link ?>" width="<?= $p->media->preview_w ?>" height="<?= $p->media->preview_h ?>" class="lazyload post_image<?= ($p->media->spoiler) ? ' is_spoiler_image' : '' ?>" data-md5="<?= $p->media->media_hash ?>" />
				<noscript>
					<a href="<?= ($p->media_link) ? $p->media_link : $p->remote_media_link ?>" target="_blank" rel="noreferrer" class="thread_image_link">
						<img src="<?= $p->media->thumb_link ?>" style="margin-left: -<?= $p->media->preview_w ?>px" <?= ($p->media->preview_w > 0 && $p->media->preview_h > 0) ? 'width="' . $p->media->preview_w . '" height="' . $p->media->preview_h . '" ' : '' ?>class="lazyload post_image<?= ($p->media->spoiler) ? ' is_spoiler_image' : '' ?>" data-md5="<?= $p->media->media_hash ?>" />
					</a>
				</noscript>
				<?php else : ?>
				<img src="<?= $p->media->thumb_link ?>" <?= ($p->media->preview_w > 0 && $p->media->preview_h > 0) ? 'width="' . $p->media->preview_w . '" height="' . $p->media->preview_h . '" ' : '' ?>class="lazyload post_image<?= ($p->media->spoiler) ? ' is_spoiler_image' : '' ?>" data-md5="<?= $p->media->media_hash ?>" />
				<?php endif; ?>
			</a>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<header>
		<div class="post_data">
			<?php if (isset($modifiers['post_show_board_name']) &&  $modifiers['post_show_board_name']): ?>
			<span class="post_show_board">/<?= $p->board->shortname ?>/</span>
			<?php endif; ?>

			<h2 class="post_title"><?= $p->title_processed ?></h2>
			<span class="post_author"><?= ($p->email_processed && $p->email_processed != 'noko') ? '<a href="mailto:' . htmlspecialchars($p->email_processed) . '">' . $p->name_processed . '</a>' : $p->name_processed ?></span>
			<span class="post_trip"><?= $p->trip_processed ?></span>
			<span class="poster_hash"><?= ($p->poster_hash_processed) ? 'ID:' . $p->poster_hash_processed : '' ?></span>
			<?php if ($p->capcode != 'N') : ?>
				<?php if ($p->capcode == 'M') : ?>
					<span class="post_level post_level_moderator">## <?= __('Mod') ?></span>
				<?php endif ?>
				<?php if ($p->capcode == 'A') : ?>
					<span class="post_level post_level_administrator">## <?= __('Admin') ?></span>
				<?php endif ?>
				<?php if ($p->capcode == 'D') : ?>
					<span class="post_level post_level_developer">## <?= __('Developer') ?></span>
				<?php endif ?>
			<?php endif; ?>

			<span class="time_wrap">
				<time datetime="<?= gmdate(DATE_W3C, $p->timestamp) ?>" <?php if ($p->board->archive) : ?> title="<?= __('4chan Time') . ': ' . gmdate('D M d H:i:s Y', $p->original_timestamp) ?>"<?php endif; ?>><?= gmdate('D M d H:i:s Y', $p->timestamp) ?></time>
			</span>

			<a href="<?= Uri::create(array('@radix',  $p->board->shortname, 'thread', $p->thread_num)) . '#'  . $num ?>" data-post="<?= $num ?>" data-function="highlight">No.</a><a href="<?= Uri::create(array('@radix',  $p->board->shortname, $quote_mode, $p->thread_num)) . '#q' . $num ?>" data-post="<?= str_replace('_', ',', $num) ?>" data-function="quote"><?= str_replace('_', ',', $num) ?></a>

			<?php if ($p->subnum > 0)   : ?><span class="post_type"><i class="icon-comment-alt" title="<?= htmlspecialchars(__('This post was made in the archive.')) ?>"></i></span><?php endif ?>
			<?php if (isset($p->media) && $p->media->spoiler == 1) : ?><span class="post_type"><i class="icon-eye-close" title="<?= htmlspecialchars(__('This post contains a spoiler image.')) ?>"></i></span><?php endif ?>
			<?php if ($p->deleted == 1) : ?><span class="post_type"><i class="icon-trash" title="<?= htmlspecialchars(__('This post was deleted from 4chan manually.')) ?>"></i></span><?php endif ?>

			<span class="post_controls">
				<?php if (isset($modifiers['post_show_view_button'])) : ?><a href="<?= Uri::create('@radix/' . $p->board->shortname . '/thread/' . $p->thread_num) . '#' . $num ?>" class="btnr parent"><?= __('View') ?></a><?php endif; ?><a href="<?= Uri::create('@radix/' . $p->board->shortname . '/report/' . $p->doc_id) ?>" class="btnr parent" data-post="<?= $p->doc_id ?>" data-post-id="<?= $num ?>" data-board="<?= htmlspecialchars($p->board->shortname) ?>" data-controls-modal="post_tools_modal" data-backdrop="true" data-keyboard="true" data-function="report"><?= __('Report') ?></a><?php if ($p->subnum > 0 || Auth::has_access('maccess.mod') || !$p->board->archive) : ?><a href="<?= Uri::create('@radix/' . $p->board->shortname . '/delete/' . $p->doc_id) ?>" class="btnr parent" data-post="<?= $p->doc_id ?>" data-post-id="<?= $num ?>" data-board="<?= htmlspecialchars($p->board->shortname) ?>" data-controls-modal="post_tools_modal" data-backdrop="true" data-keyboard="true" data-function="delete"><?= __('Delete') ?></a><?php endif; ?>
			</span>
		</div>
	</header>

	<div class="backlink_list"<?= $p->backlinks ? ' style="display:block"' : '' ?>>
		<?= __('Quoted By:') ?> <span class="post_backlink" data-post="<?= $p->num ?>"><?= $p->backlinks ? implode(' ', $p->backlinks) : '' ?></span>
	</div>

	<div class="text<?php if (preg_match('/[\x{4E00}-\x{9FBF}\x{3040}-\x{309F}\x{30A0}-\x{30FF}]/u', $p->comment_processed)) echo ' shift-jis'; ?>">
		<?= $p->comment_processed ?>
	</div>

	<?php if (Auth::has_access('maccess.mod')) : ?>
	<div class="btn-group" style="clear:both; padding:5px 0 0 0;">
		<button class="btn btn-mini" data-function="activateModeration"><?= __('Mod') ?><?php if ($p->poster_ip) echo ' ' .\Inet::dtop($p->poster_ip) ?></button>
	</div>
	<div class="btn-group post_mod_controls" style="clear:both; padding:5px 0 0 5px;">
		<button class="btn btn-mini" data-function="mod" data-board="<?= $p->board->shortname ?>" data-board-url="<?= Uri::create(array('@radix', $p->board->shortname)) ?>" data-id="<?= $p->doc_id ?>" data-action="delete_post"><?= __('Delete Post') ?></button>
		<?php if (!is_null($p->media)) : ?>
			<button class="btn btn-mini" data-function="mod" data-board="<?= $p->board->shortname ?>" data-id="<?= $p->doc_id ?>" data-action="delete_image"><?= __('Delete Image') ?></button>
			<button class="btn btn-mini" data-function="mod" data-board="<?= $p->board->shortname ?>" data-id="<?= $p->doc_id ?>" data-action="ban_image"><?= __('Ban Image') ?></button>
		<?php endif; ?>
		<?php if ($p->poster_ip) : ?>
			<button class="btn btn-mini" data-function="ban" data-controls-modal="post_tools_modal" data-backdrop="true" data-keyboard="true" data-board="<?= $p->board->shortname ?>" data-ip="<?= \Inet::dtop($p->poster_ip) ?>" data-action="ban_user"><?= __('Ban IP:') . ' ' . \Inet::dtop($p->poster_ip) ?></button>
			<button class="btn btn-mini" data-function="searchUser" data-board="<?= $p->board->shortname ?>" data-id="<?= $p->doc_id ?>" data-poster-ip="<?= \Inet::dtop($p->poster_ip) ?>"><?= __('Search IP') ?></button>
			<?php if (Preferences::get('fu.sphinx.global')) : ?>
				<button class="btn btn-mini" data-function="searchUserGlobal" data-board="<?= $p->board->shortname ?>" data-id="<?= $p->doc_id ?>" data-poster-ip="<?= \Inet::dtop($p->poster_ip) ?>"><?= __('Search IP Globally') ?></button>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<?php if ($p->reports) : ?>
		<?php foreach ($p->reports as $report) : ?>
			<div class="report_reason"><?= '<strong>' . __('Reported Reason:') . '</strong> ' . $report->reason_processed ?>
				<br/>
				<div class="ip_reporter">
					<?= \Inet::dtop($report->ip_reporter) ?>
					<button class="btn btn-mini" data-function="mod" data-report-id="<?= $report->id ?>" data-id="<?= $p->doc_id ?>" data-action="remove_report"><?= __('Delete Report') ?></button>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php endif; ?>
</article>
<br/>
