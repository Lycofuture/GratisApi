<?php
error_reporting(0);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>插件控制面板</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
    </head>
    <body>
        <style>
            a{text-decoration:none}
            a:hover,a:visited,a:active,a:link
            {
                color: rgb(0,136,255);
            }
            html{overflow-x:hidden;}
            td
            {
                padding-left: 10px;
                padding-right: 10px;
                padding-top: 3px;
                padding-bottom: 3px;
                font-size: 10px;
                color: rgb(0,136,255);
            }
            th
            {
                font-size: 10px;
                font-weight: 50;
                padding-top: 5px;
                padding-bottom: 2px;
                margin-top: -5px;
            }
            td.mpq-plugin-id
            {
                width: 10px;
            }
            td.mpq-plugin-status
            {
                width: 25px;
            }
            td.mpq-plugin-type
            {
                width: 15%;
            }
            td.mpq-plugin-name
            {
                width: 100px;
            }
            .mpq-plugin-style-left
            {
                text-align: left;
                padding-left: 5px;
            }
            tr:hover
            {
                background-color: rgb(204,232,255);
            }
        </style>
        <div class="nowMonthData">
            <table border="1" cellspacing="0" bordercolor="#ebf4fe" style="text-align: center;margin-left: -9px;margin-top: -9px;width: 735px;">
                <tr>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>状态</th>
                    <th>插件名</th>
                    <th>类型</th>
                    <th class="mpq-plugin-style-left">基本说明</th>
                </tr>
                <?php get_active_plugins(); ?>
            </table>
            <?php
            function get_active_plugins()
            {
                $dir = dirname(__FILE__).DIRECTORY_SEPARATOR.'Plugins';
                $filesnames = scandir($dir);
                $i = 0;
                $plugins = array();
                foreach($filesnames as $filename)
                {
                    if($filename!='.' &&$filename!='..')
                    {
                        $file_config = "Plugins/{$filename}/config.json";
                        if(!file_exists($file_config)) continue;
                        $file_data = file_get_contents($file_config);
                        $file_data_json = json_decode($file_data);
                        
                        $i++;
                        $plugin_status = $file_data_json -> plugin_set -> status;
                        $plugin_name = $file_data_json -> plugin_set -> name;
                        $plugin_description = $file_data_json -> plugin_set -> description;
                        if($plugin_status == true)
                        {
                            $plugin_status = "启用";
                        }
                        else
                        {
                            $plugin_status = "禁用";
                        }
                        echo '<tr>
                    <td class="mpq-plugin-id">'.$i.'</td>
                    <td class="mpq-plugin-status" p_name="'.$filename.'"><a href="#'.$plugin_status.'">'.$plugin_status.'</a></td>
                    <td class="mpq-plugin-name">'.$plugin_name.'</td>
                    <td class="mpq-plugin-type">'.$plugin_type.'</td>
                    <td class="mpq-plugin-description mpq-plugin-style-left">'.$plugin_description.'</td>
                </tr>';
                    }
                }
                return $plugins;
            }
            ?>
        </div>
        <iframe id="plugin-set" style="display:none;"></iframe>
        <script>
        var plugins = document.getElementsByClassName("mpq-plugin-status");
        var plugin_name,plugin_status;
        for(var i=0;i<plugins.length;i++)
        {
            var p_name = plugins[i];
            p_name.addEventListener('click', function(){
                plugin_name = this.getAttribute("p_name");
                //console.log(plugin_name);
                plugin_option(plugin_name);
                plugin_status = this.innerHTML;
                if(plugin_status.indexOf("启用") > -1)
                {
                    this.innerHTML = '<a href="#禁用">禁用</a>';
                }
                else
                {
                    this.innerHTML = '<a href="#禁用">启用</a>';
                }
            }, false);
        }
        
        function plugin_option(plugin_name)
        {
            document.querySelector("#plugin-set").src = "Set.php?mod=plugin_switch&app_name=" + plugin_name;
        }
        </script>
    </body>
</html>