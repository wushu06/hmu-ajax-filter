<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\NamespaceHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use SlevomatCodingStandard\Helpers\UseStatementHelper;
use const T_COMMA;
use const T_ELLIPSIS;
use const T_FUNCTION;
use const T_NEW;
use const T_NS_SEPARATOR;
use const T_OBJECT_OPERATOR;
use const T_OPEN_PARENTHESIS;
use const T_OPEN_TAG;
use const T_STRING;
use function in_array;
use function sprintf;
use function substr;

class OptimizedFunctionsWithoutUnpackingSniff implements Sniff
{

	public const CODE_UNPACKING_USED = 'UnpackingUsed';

	private const SPECIAL_FUNCTIONS = [
		'array_key_exists',
		'array_slice',
		'boolval',
		'call_user_func',
		'call_user_func_array',
		'chr',
		'count',
		'doubleval',
		'defined',
		'floatval',
		'func_get_args',
		'func_num_args',
		'get_called_class',
		'get_class',
		'gettype',
		'in_array',
		'intval',
		'is_array',
		'is_bool',
		'is_double',
		'is_float',
		'is_long',
		'is_int',
		'is_integer',
		'is_null',
		'is_object',
		'is_real',
		'is_resource',
		'is_string',
		'ord',
		'strlen',
		'strval',
	];

	/**
	 * @return mixed[]
	 */
	public function register(): array
	{
		return [T_STRING];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 */
	public function process(File $phpcsFile, $pointer): void
	{
		$previousTokenPointer = TokenHelper::findPreviousEffective($phpcsFile, $pointer - 1);
		$openBracketPointer = TokenHelper::findNextEffective($phpcsFile, $pointer + 1);
		$tokens = $phpcsFile->getTokens();

		if ($openBracketPointer === null || $tokens[$openBracketPointer]['code'] !== T_OPEN_PARENTHESIS) {
			return;
		}

		if (in_array($tokens[$previousTokenPointer]['code'], [T_FUNCTION, T_NEW, T_OBJECT_OPERATOR], true)) {
			return;
		}
		/** @var int $tokenBeforeInvocationPointer */
		$tokenBeforeInvocationPointer = TokenHelper::findPreviousExcluding($phpcsFile, [T_STRING, T_NS_SEPARATOR], $pointer);
		$invokedName = TokenHelper::getContent($phpcsFile, $tokenBeforeInvocationPointer + 1, $pointer);
		$useName = sprintf('function %s', $invokedName);

		/** @var int $openTagPointer */
		$openTagPointer = TokenHelper::findPrevious($phpcsFile, T_OPEN_TAG, $pointer);
		$uses = UseStatementHelper::getUseStatements($phpcsFile, $openTagPointer);
		if ($invokedName[0] === '\\') {
			$invokedName = substr($invokedName, 1);
		} elseif (isset($uses[$useName]) && $uses[$useName]->isFunction()) {
			$invokedName = $uses[$useName]->getFullyQualifiedTypeName();
		} elseif (NamespaceHelper::findCurrentNamespaceName($phpcsFile, $pointer) !== null) {
			return;
		}

		if (!in_array($invokedName, self::SPECIAL_FUNCTIONS, true)) {
			return;
		}

		$closeBracketPointer = $tokens[$openBracketPointer]['parenthesis_closer'];

		if (TokenHelper::findNextEffective($phpcsFile, $openBracketPointer + 1, $closeBracketPointer + 1) === $closeBracketPointer) {
			return;
		}

		do {
			$lastArgumentSeparatorPointer = TokenHelper::findPrevious($phpcsFile, [T_COMMA], $closeBracketPointer, $openBracketPointer);
		} while ($lastArgumentSeparatorPointer !== null && $tokens[$lastArgumentSeparatorPointer]['level'] !== $tokens[$openBracketPointer]['level']);

		if ($lastArgumentSeparatorPointer === null) {
			$lastArgumentSeparatorPointer = $openBracketPointer;
		}

		/** @var int $nextTokenAfterSeparatorPointer */
		$nextTokenAfterSeparatorPointer = TokenHelper::findNextEffective($phpcsFile, $lastArgumentSeparatorPointer + 1, $closeBracketPointer);

		if ($tokens[$nextTokenAfterSeparatorPointer]['code'] !== T_ELLIPSIS) {
			return;
		}

		$phpcsFile->addError(
			sprintf('Function %s is specialized by PHP and should not use argument unpacking.', $invokedName),
			$nextTokenAfterSeparatorPointer,
			self::CODE_UNPACKING_USED
		);
	}

}
