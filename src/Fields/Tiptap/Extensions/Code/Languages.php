<?php

namespace Arniro\Admin\Fields\Tiptap\Extensions\Code;

use Illuminate\Support\Arr;

class Languages {
    const PHP = 'php';
    const JAVASCRIPT = 'javascript';
    const TYPESCRIPT = 'typescript';
    const CSS = 'css';
    const SCSS = 'scss';
    const LESS = 'less';
    const JSON = 'json';
    const PERL = 'perl';
    const PYTHON = 'python';
    const RUBY = 'ruby';
    const CPP = 'cpp';
    const OBJECTIVEC = 'objectivec';
    const GO = 'go';
    const JAVA = 'java';
    const SQL = 'sql';
    const MARKDOWN = 'markdown';
    const BASH = 'bash';
    const HTTP = 'http';
    const MAKEFILE = 'makefile';
    const NGINX = 'nginx';
    const YAML = 'yaml';

    public static function all()
    {
        return [
            static::PHP => 'PHP',
            static::JAVASCRIPT => 'JavaScript',
            static::TYPESCRIPT => 'TypeScript',
            static::CSS => 'CSS',
            static::SCSS => 'SCSS',
            static::LESS => 'LESS',
            static::JSON => 'JSON',
            static::PERL => 'Perl',
            static::PYTHON => 'Python',
            static::RUBY => 'Ruby',
            static::CPP => 'C++',
            static::OBJECTIVEC => 'Objective-C',
            static::GO => 'Go',
            static::JAVA => 'Java',
            static::SQL => 'SQL',
            static::MARKDOWN => 'Markdown',
            static::BASH => 'Bash',
            static::HTTP => 'HTTP',
            static::MAKEFILE => 'Makefile',
            static::NGINX => 'Nginx',
            static::YAML => 'Yaml',
        ];
    }

    public static function get($languages)
    {
        $languages = array_flip($languages);

        foreach ($languages as $language => $label) {
            $languages[$language] = Arr::get(static::all(), $language);
        }

        return $languages;
    }
}
