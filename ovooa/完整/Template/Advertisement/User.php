<?php
$data = @file_get_Contents(__DIR__.'/./User.json');
$data = json_decode($data, true);
if(!$data){
    echo '<div class="mdui-card mdui-m-t-2 ">';
    echo "\n";
    echo '                <div class="mdui-card-primary "> <a class="mdui-list-item mdui-ripple friendlinks" href="/?action=ad&amp;title=微结云&amp;url=http://idc.1il1.com/"> ';
    echo "\n";
    echo '                        <div class="mdui-list-item-content">';
    echo "\n";
    echo '                            微结云 - 品质级的云服务器、虚拟主机、服务器租用托管服务提供商';
    echo "\n";
    echo '                        </div> </a> ';
    echo "\n";
    echo '                    <div class="mdui-card-content"> ';
    echo "\n";
    echo '                        <div class="mdui-row mdui-text-center"> ' ;
    echo "\n";
    echo '                            <div class="mdui-col-md-4 ">';
    echo "\n";
    echo '                                <a href="/?action=ad&amp;title=微结云&amp;url=http://idc.1il1.com/"> ';
    echo "\n";
    echo '                                    <img src="http://idc.1il1.com/logo.png" style="width:100%;height:100%" />';
    echo "\n";
    echo '                                </a> ';
    echo "\n";
    echo '                            </div> ';
    echo "\n";
    echo '                        </div> ';
    echo "\n";
    echo '                    </div> ';
    echo "\n";
    echo '                </div> ';
    echo "\n";
    echo '            </div>';
    echo "\n";
}else{
    foreach($data as $v){
        echo '<div class="mdui-card mdui-m-t-2 ">';
        echo "\n";
        echo '                <div class="mdui-card-primary "> <a class="mdui-list-item mdui-ripple friendlinks" href="/?action=ad&amp;title='.$v['title'].'&amp;url='.$v['url'].'"> ';
        echo "\n";
        echo '                        <div class="mdui-list-item-content">';
        echo "\n";
        echo '                            '.$v['description'];//微结云 - 品质级的云服务器、虚拟主机、服务器租用托管服务提供商';
        echo "\n";
        echo '                        </div> </a> ';
        echo "\n";
        echo '                    <div class="mdui-card-content"> ';
        echo "\n";
        echo '                        <div class="mdui-row mdui-text-center"> ';
        echo "\n";
        echo '                            <div class="mdui-col-md-4 ">';
        echo "\n";
        echo '                                <a href="/?action=ad&amp;title='.$v['title'].'&amp;url='.$v['url'].'"> ';
        echo "\n";
        echo '                                    <img src="'.$v['logo'].'" style="width:100%;height:100%" />';
        echo "\n";
        echo '                                </a> ';
        echo "\n";
        echo '                            </div> ';
        echo "\n";
        echo '                        </div> ';
        echo "\n";
        echo '                    </div> ';
        echo "\n";
        echo '                </div> ';
        echo "\n";
        echo '            </div>';
        echo "\n";
    }
}