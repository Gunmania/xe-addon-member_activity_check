<?php
	/** 
	* @file addon member_activity_check.addon.php 
	* @author Gunmania (d.gunmania@gmail.com) 
	* @brief 회원의 활동 내역(글, 댓글)을 조회하여 조건 미달 시 모듈 접근을 불허
	**/ 
	if(!defined('__XE__'))	exit();
	
	$logged_info = Context::get('logged_info');
	
	//관리자는 예외
	if($logged_info->is_admin == 'Y')	return;

	if($called_position != 'before_display_content')	return;

	$obj->s_member_srl = $logged_info->member_srl;
	$document_output = executeQuery('addons.member_activity_check.getDocumentCount', $obj);
	$comment_output = executeQuery('addons.member_activity_check.getCommentCount', $obj);

	$docu_count = $document_output->data->count;
	$comm_count = $comment_output->data->count;

	//활동 기준치를 만족할 때
	if ($docu_count >= $addon_info->document && $comm_count >= $addon_info->comment) {
		return;
	}
	
	//활동 기준치를 만족하지 못할 때
	else {
		header("Content-Type: text/html; charset=UTF-8");
		echo '<script>alert("활동(글, 댓글 작성)이 부족하여 접근이 불가능합니다.\n\n접근 권한 : 글 '.$addon_info->document.'개, 댓글 '.$addon_info->comment.'개 이상\n현재 활동 내역 : 글 '.$docu_count.'개, 댓글 '.$comm_count.'개 작성");</script>';
		echo '<script>window.location.href = "/";</script>';
		exit();
	}

?>