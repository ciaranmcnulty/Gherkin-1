<?php
declare(strict_types=1);

namespace Behat\Gherkin\Parsica;

use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioNode;
use Verraes\Parsica\Parser;
use function Verraes\Parsica\alphaNumChar;
use function Verraes\Parsica\blank;
use function Verraes\Parsica\char;
use function Verraes\Parsica\choice;
use function Verraes\Parsica\collect;
use function Verraes\Parsica\eof;
use function Verraes\Parsica\eol;
use function Verraes\Parsica\keepFirst;
use function Verraes\Parsica\many;
use function Verraes\Parsica\punctuationChar;
use function Verraes\Parsica\skipHSpace;
use function Verraes\Parsica\skipSpace;
use function Verraes\Parsica\string;
use function Verraes\Parsica\zeroOrMore;

function token(Parser $parser) : Parser
{
    return keepFirst($parser, skipHSpace());
}

function keyword(string $keyword, bool $withColon) : Parser
{
    return \Behat\Gherkin\Parsica\token(keepFirst(string($keyword), char($withColon ? ':' : ' ')));
}

/** 
 * A single line of text trimmed of whitespace at both ends 
 * 
 * @todo align with the cucumber concept of whitespace
 */
function textLine() : Parser
{
    return keepFirst(
        zeroOrMore(
            choice(
                alphaNumChar(),
                punctuationChar(),
                blank()
            )
        ),
        eol()->or(eof())
    )->map(fn(?string $str) => trim((string)$str));
}
