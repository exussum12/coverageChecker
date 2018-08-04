<?php
namespace exussum12\CoverageChecker;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Parser;
use PhpParser\ParserFactory;

class FileParser
{
    protected $sourceCode = '';
    protected $classes = [];
    protected $functions = [];

    public function __construct($sourceCode)
    {
        $this->sourceCode = $sourceCode;
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $this->parse($parser);
    }

    /**
     * @return CodeLimits[]
     */
    public function getClassLimits()
    {
        return $this->classes;
    }

    /**
     * @return CodeLimits[]
     */
    public function getFunctionLimits()
    {
        return $this->functions;
    }

    protected function parse(Parser $parser)
    {
        try {
            $ast = $parser->parse($this->sourceCode);
        } catch (Error $exception) {
            return;
        }

        foreach ($ast as $node) {
            $this->handleNode($node);
        }
    }

    protected function getCodeLimits(Node $node): CodeLimits
    {
        $startLine = $node->getAttribute('startLine');
        $endLine = $node->getAttribute('endLine');
        if ($node->getDocComment()) {
            $startLine = $node->getDocComment()->getLine();
        }

        return new CodeLimits($startLine, $endLine);
    }

    protected function addClass($classLimits)
    {
        $this->classes[] = $classLimits;
    }

    protected function addFunction($classLimits)
    {
        $this->functions[] = $classLimits;
    }

    protected function handleClass(ClassLike $node)
    {
        $this->addClass($this->getCodeLimits($node));

        foreach ($node->getMethods() as $function) {
            $this->handleNode($function);
        }
    }

    protected function handleFunction(FunctionLike $node)
    {
        $this->addFunction($this->getCodeLimits($node));
    }

    private function handleNamespace(Namespace_ $node)
    {
        foreach ($node->stmts as $part) {
            $this->handleNode($part);
        }
    }

    protected function handleNode(Node $node)
    {
        $type = $node->getType();
        if ($type == 'Stmt_Namespace') {
            $this->handleNamespace($node);
        }

        if ($type == 'Stmt_Class') {
            $this->handleClass($node);
        }

        if ($type == "Stmt_Function" || $type == "Stmt_ClassMethod") {
            $this->handleFunction($node);
        }
    }
}
