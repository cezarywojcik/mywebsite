<?php

// very basic markdown parsing for my own needs

class CWMarkdown {
    // ---- [ protected vars ] ------------------------------------------------
    protected $originalString = '';
    protected $HTMLString = '';

    // ---- [ constructor ] ---------------------------------------------------
    function __construct($originalString) {
        $this->originalString = $originalString;
        $this->parse();
    }

    // ---- [ public functions ] ----------------------------------------------
    public function getHTMLString() {
        return $this->HTMLString;
    }

    // ---- [ protected functions ] -------------------------------------------
    protected function parse() {
        $paragraphs = explode("\n", $this->originalString);
        foreach ($paragraphs as $paragraph) {
            $this->parseParagraph($paragraph);
        }
    }

    protected function parseParagraph($paragraph) {
        $result = '';
        if (strlen($paragraph) > 1) {
            if (substr($paragraph, 0, 4) === '    ') {
                $paragraph = '<pre>' . substr($paragraph, 4,
                    strlen($paragraph)) . '</pre>';
            }
            $temp = explode(" ", $paragraph);
            $firstWord = reset($temp);
            switch ($firstWord) {
                case '#':
                    $result = $this->tagReplace1('#', 'h1', $paragraph);
                    break;
                case '##':
                    $result = $this->tagReplace1('##', 'h2', $paragraph);
                    break;
                case '###':
                    $result = $this->tagReplace1('###', 'h3', $paragraph);
                    break;
                case '####':
                    $result = $this->tagReplace1('####', 'h4', $paragraph);
                    break;
                case '#####':
                    $result = $this->tagReplace1('#####', 'h5', $paragraph);
                    break;
                case '######':
                    $result = $this->tagReplace1('######', 'h6', $paragraph);
                    break;
                case '*':
                    $result = $this->tagReplace2('*', 'li', $paragraph);
                    break;
                case '>':
                    $result = $this->tagReplace2('>', 'blockquote',
                        $paragraph);
                    break;
                default:
                    $result = '<p>' . $paragraph . '</p>';
            }
            $result = $this->imageReplace($result);
            $result = $this->linkReplace($result);
            $result = $this->tagReplace3('**', 'strong', $result);
            $result = $this->tagReplace3('*', 'i', $result);
            $result = $this->tagReplace3('`', 'code', $result);
            $this->HTMLString .= $result;
        }
    }

    // ---- [ regex parsing functions ] ---------------------------------------

    protected function tagReplace1($symbol, $tag, $string) {
        $pattern = '/^\\' . $symbol . ' | \\' . $symbol . '$/';
        $inner = preg_replace($pattern, '', $string);
        return '<' . $tag . '>' . $inner . '</' . $tag . '>';
    }

    protected function tagReplace2($symbol, $tag, $string) {
        $pattern = '/^\\' . $symbol . ' /';
        $inner = preg_replace($pattern, '', $string);
        return '<' . $tag . '>' . $inner . '</' . $tag . '>';
    }

    protected function tagReplace3($symbol, $tag, $string) {
        $regexSymbol = '';
        foreach (str_split($symbol) as $char) {
            $regexSymbol .= '\\' . $char;
        }
        $pattern = '/' . $regexSymbol . '(.*?)' . $regexSymbol . '/';
        return preg_replace($pattern,
            '<' . $tag . '>' . "$1". '</' . $tag . '>', $string);
    }

    protected function imageReplace($string) {
        $pattern = '/!\[(.*?)\]\((.*?)\)/';
        return preg_replace($pattern,
            '<img class="article-image" alt="$1" src="$2">', $string);
    }

    protected function linkReplace($string) {
        $pattern = '/\[(.*?)\]\((.*?)\)/';
        return preg_replace($pattern, '<a href="$2">$1</a>', $string);
    }
}
