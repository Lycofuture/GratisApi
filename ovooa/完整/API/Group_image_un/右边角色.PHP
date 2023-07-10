/*
$array = read_all('./S');
$rand = array_rand($array,1);
$fill = $array[$rand]['name'];
$fill = new imagick($fill);
$fill->setformat('png');
$fill->resizeImage(550,550,Imagick::FILTER_LANCZOS,1);
$width = $fill->getimagewidth();
$height = $fill->getimageheight();
if(!$fill->getImageAlphaChannel()){
    $fill->setImageAlphaChannel(Imagick::ALPHACHANNEL_SET);
}

$filll = new imagick();
$filll->newPseudoImage($width,$height,"gradient: transparent-black");
$filll->rotateImage('',90);
$fill->compositeImage($filll,imagick::COMPOSITE_DSTOUT,'-'.($width/6),0);
$canvas = new Imagick();
$canvas->newImage($width,$height,'none');
$canvas->setImageFormat('png');
$canvas->compositeImage($fill,imagick::COMPOSITE_SRCOVER,20,10);
*/
/*
$refl = clone $fill;
//$refl->flipImage();
$gradient=new Imagick();
$gradient->newPseudoImage($refl->getImageWidth()+10,$refl->getImageHeight()+10,"gradient:transparent-black");
$refl->compositeImage($gradient,imagick::COMPOSITE_DSTOUT,0,0);
$canvas = new Imagick();
$width = $fill->getImageWidth()+40;
$height = ($fill->getImageHeight()*2)+30;
$canvas->newImage($width,$height,'none');
$canvas->setImageFormat('png');

$canvas->compositeImage($fill,imagick::COMPOSITE_SRCOVER,20,10);
$canvas->compositeImage($refl,imagick::COMPOSITE_SRCOVER,20,$fill->getImageHeight()+10);*/
