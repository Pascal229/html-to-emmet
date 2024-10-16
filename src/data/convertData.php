<?php

header('Content-Type: application/json');

// replace all < and > with &lt; and &gt;

function escape($str)
{
    return str_replace(array('<', '>', '\'', '"'), array('&lt;', '&gt;', '&apos;', '&quot;'), $str);
}


$noCloseTags = [
    "meta",
    "link",
    "input",
    "img",
    "br",
    "area",
    "base",
    "col",
    "embed",
    "hr",
    "img",
    "param",
    "source",
    "wbr"
];



switch ($_POST['action']) {
    case 'convertData': {
            $input = $_POST['data'];
            // calculations go here
            // $re = '/<([\w\-]+)\s*((?:[\w\-]+=".+")*)(?:[\s]*[\/]*>[\s]*([^<]*))/m';

            // /<([\w\-]+)\s*((?:[\w\-]+=".+")*)(?:[\s]*[\/]*>[\s]*([^<]*))/

            // /<([\/]{0,1}[\w\-]+)\s*((?:[\w\-]+=".+")*)(?:[\s]*[\/]*>[\s]*([^<]*))/

            // /<([\/]{0,1}[\w\-]+)\s*((?:[\s\w\-]+=".+"[\s]*)*)(?:[\s]*[\/]*>[\s]*([^<]*)){0,1}
            
            
            $tags = [];
            $prefix = "";
            $suffix = "";

            if (strpos($input, '<!DOCTYPE html>') !== false) {
                $prefix = "<!DOCTYPE html> \n"; 
                $input = str_replace('<!DOCTYPE html>', '', $input);
                // print_r($prefix);
            }

            preg_match_all('/<([\/]{0,1}[\w\-]+)\s*((?:[\s\w\-]+="[^"]*"[\s]*)*)(?:[\s]*[\/]*>[\s]*([^<]*)){0,1}/', $input, $tags, PREG_SET_ORDER, 0);

            $output = '';
            $count = 0;
            $symbol = [];

            foreach ($tags as $tag) {
                $actuelSymbol = '';
                $parts = ["", "", "", ""]; 
                
                // print_r($tag[1]);
                if (!($tag[1][0] == '/')) {
                    $bracket = "(";
                    $symbol[$count] = '>';
                    foreach($noCloseTags as $noCloseTag) {
                        if($tag[1] == $noCloseTag) {
                            $bracket = "";
                            $symbol[$count] = '+';
                        }
                    }
                    $tag[1] = str_replace('(', "", $tag[1]);
                    $tag[1] = str_replace(')', "", $tag[1]);
                    $parts[1] = str_replace($tag[1], $bracket . $tag[1], $tag[1]);
                    
                    if (isset($tag[2])) {
                        $tag[2] = str_replace('[', "", $tag[2]);
                        $tag[2] = str_replace(']', "", $tag[2]);
                        $parts[2] = str_replace($tag[2], '[' . $tag[2] . ']', $tag[2]);
                    }
                    
                    if (isset($tag[3])) {
                        $tag[3] = str_replace('{', "(", $tag[3]);
                        $tag[3] = str_replace('}', ")", $tag[3]);
                        $parts[3] = str_replace($tag[3], '{' . $tag[3] . '}', $tag[3]);
                    }
                    
                } else {
                    $parts[1] = str_replace($tag[1], ')', $tag[1]);
                    $symbol[$count] = '+';
                    
                    foreach($noCloseTags as $noCloseTag) {
                        if($tag[1] == '/'.$noCloseTag) {
                            $parts[1] = str_replace($tag[1], '', $tag[1]);
                        }
                    }
                }
                // print_r($tag[1].'|'.$tag[2].'|'.$tag[3]."\n");
                
                
                // for($i = 1; $i < 2; $i++) {
                    //     $tag[$i] = str_replace("\n", "", $tag[$i]);
                    //     $tag[$i] = str_replace("\r", "", $tag[$i]);
                    //     $tag[$i] = str_replace(" ", "", $tag[$i]);
                    // }
                    
                    // print_r($parts);
                    
                    
                    if (!($tag[1][0] == '/')) {
                        $actuelSymbol = isset($symbol[$count - 1]) ? $symbol[$count - 1] : '';
                    }
                    
                    
                    // print_r($output . $actuelSymbol . $temp);
                    // print_r($parts);                
                    
                    $element = $actuelSymbol . $parts[1].$parts[2].$parts[3];
                    
                    $output .= $element;
                    
                    // $output = substr($output, 0, -1);
                    
                    
                    $count++;
                }
                // print_r($output);
                
                $output = $prefix.$output.$suffix;

                $output = escape($output);
                
                // $output = substr($output, 0, -1);
                // $output .= ')';
                // $output = substr($output, 1);
                
                // $output = htmlspecialchars($output);
                // $output = escape($output);
                
                
                echo json_encode(['result' => $output]);
                die();
            }
        }


echo json_encode(['error' => 'no Action']);
die();



$tagList = [
    '<!DOCTYPE>',
    '<a>',
    '<abbr>',
    '<acronym>',
    '<address>',
    '<applet>',
    '<area>',
    '<article>',
    '<aside>',
    '<audio>',
    '<b>',
    '<base>',
    '<basefont>',
    '<bdi>',
    '<bdo>',
    '<big>',
    '<blockquote>',
    '<body>',
    '<br>',
    '<button>',
    '<canvas>',
    '<caption>',
    '<center>',
    '<cite>',
    '<code>',
    '<col>',
    '<colgroup>',
    '<data>',
    '<datalist>',
    '<dd>',
    '<del>',
    '<details>',
    '<dfn>',
    '<dialog>',
    '<dir>',
    '<ul>',
    '<div>',
    '<dl>',
    '<dt>',
    '<em>',
    '<embed>',
    '<fieldset>',
    '<figcaption>',
    '<figure>',
    '<font>',
    '<footer>',
    '<form>',
    '<frame>',
    '<frameset>',
    '<h1>',
    '<h2>',
    '<h3>',
    '<h4>',
    '<h5>',
    '<h6>',
    '<head>',
    '<header>',
    '<hr>',
    '<html>',
    '<i>',
    '<iframe>',
    '<img>',
    '<input>',
    '<ins>',
    '<kbd>',
    '<label>',
    '<input>',
    '<legend>',
    '<fieldset>',
    '<li>',
    '<link>',
    '<main>',
    '<map>',
    '<mark>',
    '<meta>',
    '<meter>',
    '<nav>',
    '<noframes>',
    '<noscript>',
    '<object>',
    '<ol>',
    '<optgroup>',
    '<option>',
    '<output>',
    '<p>',
    '<param>',
    '<picture>',
    '<pre>',
    '<progress>',
    '<q>',
    '<rp>',
    '<rt>',
    '<ruby>',
    '<s>',
    '<samp>',
    '<script>',
    '<section>',
    '<select>',
    '<small>',
    '<source>',
    '<span>',
    '<strike>',
    '<strong>',
    '<style>',
    '<sub>',
    '<summary>',
    '<details>',
    '<sup>',
    '<svg>',
    '<table>',
    '<tbody>',
    '<td>',
    '<template>',
    '<textarea>',
    '<tfoot>',
    '<th>',
    '<thead>',
    '<time>',
    '<title>',
    '<tr>',
    '<track>',
    '<tt>',
    '<u>',
    '<ul>',
    '<var>',
    '<video>',
    '<wbr>',
    '</a>',
    '</abbr>',
    '</acronym>',
    '</address>',
    '</applet>',
    '</area>',
    '</article>',
    '</aside>',
    '</audio>',
    '</b>',
    '</base>',
    '</basefont>',
    '</bdi>',
    '</bdo>',
    '</big>',
    '</blockquote>',
    '</body>',
    '</br>',
    '</button>',
    '</canvas>',
    '</caption>',
    '</center>',
    '</cite>',
    '</code>',
    '</col>',
    '</colgroup>',
    '</data>',
    '</datalist>',
    '</dd>',
    '</del>',
    '</details>',
    '</dfn>',
    '</dialog>',
    '</dir>',
    '</ul>',
    '</div>',
    '</dl>',
    '</dt>',
    '</em>',
    '</embed>',
    '</fieldset>',
    '</figcaption>',
    '</figure>',
    '</font>',
    '</footer>',
    '</form>',
    '</frame>',
    '</frameset>',
    '</h1>',
    '</h2>',
    '</h3>',
    '</h4>',
    '</h5>',
    '</h6>',
    '</head>',
    '</header>',
    '</hr>',
    '</html>',
    '</i>',
    '</iframe>',
    '</img>',
    '</input>',
    '</ins>',
    '</kbd>',
    '</label>',
    '</input>',
    '</legend>',
    '</fieldset>',
    '</li>',
    '</link>',
    '</main>',
    '</map>',
    '</mark>',
    '</meta>',
    '</meter>',
    '</nav>',
    '</noframes>',
    '</noscript>',
    '</object>',
    '</ol>',
    '</optgroup>',
    '</option>',
    '</output>',
    '</p>',
    '</param>',
    '</picture>',
    '</pre>',
    '</progress>',
    '</q>',
    '</rp>',
    '</rt>',
    '</ruby>',
    '</s>',
    '</samp>',
    '</script>',
    '</section>',
    '</select>',
    '</small>',
    '</source>',
    '</span>',
    '</strike>',
    '</strong>',
    '</style>',
    '</sub>',
    '</summary>',
    '</details>',
    '</sup>',
    '</svg>',
    '</table>',
    '</tbody>',
    '</td>',
    '</template>',
    '</textarea>',
    '</tfoot>',
    '</th>',
    '</thead>',
    '</time>',
    '</title>',
    '</tr>',
    '</track>',
    '</tt>',
    '</u>',
    '</ul>',
    '</var>',
    '</video>',
    '</wbr>',
];
