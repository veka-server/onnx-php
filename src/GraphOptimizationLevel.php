<?php

namespace Onnx;

enum GraphOptimizationLevel: int
{
    case None = 0;
    case Basic = 1;
    case Extended = 2;
    case All = 99;
}
