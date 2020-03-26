<?php
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

/**
 * 文件备份
 */
class FileBackupController extends AuthController
{
	public function index()
	{
		layout(false);
		import('Org.Util.FileToZip');
		// 打包下载
		$cur_file='./Public/Upload/';  // 文件所在目录
		$save_path='./Public/FileBackup/'; // zip包文件目录
		$zip = new \ZipArchive();
		$zipname=date('YmdHis',time());
		$fileToZip=$save_path.$zipname.'.zip';
		$zip->open($fileToZip, 1);
		$scandir=new \traverseDir($cur_file,$save_path);
		$res=$scandir->folderToZip($cur_file, $zip);
		if($res===true)
		{
			if($zip->close()===true)
			{
				//设置打包完自动下载
				$dw=new \download($zipname.'.zip',$save_path);
				//下载文件
				$res_down=$dw->getfiles();
				if($res_down!==false)
				{
					//下载完成后要进行删除
					unlink($fileToZip);
				}else {
					$this->error('对不起,您要下载的文件不存在');
				}
			}else {
				// 删除已压缩的备份文件
				unlink($fileToZip);
			}
		}else {
			$this->error('文件备份失败，请联系网站管理员');
		}
	}
}
?>