<?php

namespace Tests\Unit;

use App\Support\HtmlSanitizer;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_it_removes_script_tags_with_their_content(): void
    {
        $this->assertSame('<p>ok</p>', HtmlSanitizer::clean('<p>ok</p><script>alert(1)</script>'));
    }

    public function test_it_removes_event_handler_attributes(): void
    {
        $this->assertSame(
            '<img src="x" alt="a" />',
            HtmlSanitizer::clean('<img src="x" onerror="alert(1)" alt="a">')
        );

        $this->assertSame(
            '<div>texte</div>',
            HtmlSanitizer::clean('<div onmouseover="alert(1)">texte</div>')
        );
    }

    public function test_it_blocks_dangerous_urls(): void
    {
        $this->assertSame(
            '<a rel="noopener noreferrer">clic</a>',
            HtmlSanitizer::clean('<a href="javascript:alert(1)">clic</a>')
        );

        $this->assertSame(
            '<a href="https://example.com" rel="noopener noreferrer">ok</a>',
            HtmlSanitizer::clean('<a href="https://example.com" onclick="x()">ok</a>')
        );

        $this->assertSame(
            '<a rel="noopener noreferrer">x</a>',
            HtmlSanitizer::clean("<a href=\"jav\tascript:alert(1)\">x</a>")
        );

        $this->assertSame(
            '<img />',
            HtmlSanitizer::clean('<img src="data:image/svg+xml;base64,PHN2Zz48c2NyaXB0PmFsZXJ0KDEpPC9zY3JpcHQ+PC9zdmc+">')
        );
    }

    public function test_it_keeps_allowed_formatting_and_utf8_characters(): void
    {
        $this->assertSame(
            '<p>Bonjour <strong>monde</strong> &amp; café</p>',
            HtmlSanitizer::clean('<p>Bonjour <strong>monde</strong> &amp; café</p>')
        );
    }

    public function test_it_preserves_line_breaks(): void
    {
        $this->assertSame(
            '<p>Ligne 1<br />Ligne 2</p>',
            HtmlSanitizer::clean('<p>Ligne 1<br />Ligne 2</p>')
        );
    }

    public function test_it_removes_forbidden_tags_with_their_content(): void
    {
        $this->assertSame('safe', HtmlSanitizer::clean('<iframe src="https://evil.test"></iframe>safe'));
        $this->assertSame('', HtmlSanitizer::clean('<style>body{display:none}</style>'));
    }

    public function test_it_handles_empty_input(): void
    {
        $this->assertSame('', HtmlSanitizer::clean(null));
        $this->assertSame('', HtmlSanitizer::clean(''));
        $this->assertSame('', HtmlSanitizer::clean('   '));
    }
}
