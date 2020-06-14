<{{ $phpInit }}

namespace App{{ $modelDir ? '\\'.ucfirst($modelDir) : '' }};

use Illuminate\Database\Eloquent\Model;

class {{ ucfirst($modelName) }}Model extends Model
{
    protected $guarded = [];
}
