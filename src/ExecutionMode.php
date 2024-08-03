<?php

namespace Onnx;

enum ExecutionMode: int
{
    case Sequential = 0;
    case Parallel = 1;
}
