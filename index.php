<!doctype html>
<?
if(!empty($_POST['str'])){
    include('font.php');
    $dir='tmp';
    $includeLatin1=false;
    $data=file_get_contents('DroidSansFallback.ttf');
    $font=new Font($data);
    $str=$_POST['str'];
    $filename=$dir."/".md5($str);
    if(!file_exists($filename.".ttf")){
        $fontData=$font->getSubset($str,$includeLatin1);
        file_put_contents($filename.".ttf",$fontData);

        exec("./fixfont.pe $filename.ttf $filename-fix.ttf");
        exec("mv $filename-fix.ttf $filename.ttf");
        exec("ttf2eot $filename.ttf > $filename.eot");
    }
}
?>
<html>
    <head>
        <title>test</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <style type="text/css">
    
            @font-face {
                font-family: "Droid subset font-ui";
                src: url(tmp/droid-subset-ui.eot);
            }
            @font-face {
                font-family: "Droid subset font-ui";
                src: url(tmp/droid-subset-ui.ttf);
            }
            textarea,p,input,label{
                font-size:36px;
            }
            input,label{
                font-family: "Droid subset font-ui";
                margin:5px;
            }
            
            textarea{
                padding:3px;
                width:90%;
                height:200px;
                display:block;
            }
        <?if(!empty($_POST['str'])):?>
            @font-face {
                font-family: "Droid subset font";
                src: url(<?=$filename?>.eot);
            }
            @font-face {
                font-family: "Droid subset font";
                src: url(<?=$filename?>.ttf);
            }
            p.demo{
                width:90%;
                background:#EEE;
                border:#999 1px solid;
                font-family: "Droid subset font";
            }
        <?endif?>
        </style>
    </head>
    <body>
        <label>請輸入需要的字：</label>
        <form method="post">
            <textarea name="str"><?=!empty($_POST['str'])?htmlspecialchars($_POST['str']):''?></textarea>
            <input type="submit">
        </form>
        <?if(!empty($_POST['str'])):?>
            <p>
            檔案：
            <a href="<?=$filename?>.eot">eot</a>
            <a href="<?=$filename?>.eot">ttf</a>
            <p>
            <h2>DEMO:</h2>
            <p class="demo"><?=!empty($_POST['str'])?nl2br(htmlspecialchars($_POST['str'])):''?></p>
        <?endif;?>
    </body>
</html>

