<{{ $phpInit }}

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create{{ $modelName }}Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('{{ strtolower(Illuminate\Support\Str::plural($modelName)) }}', function (Blueprint $table) {
            $table->id();
            @foreach ($column_name as $index => $col_name)
                @if ($col_name != null || $col_name != '')
                    @php echo '$table->'.$column_type[$index].'(\''.$col_name.'\')';if($column_unsigned[$index] != '0'){echo'->unsigned()';echo';';}else{if($column_null[$index] != '0')echo'->nullable()';echo';';} @endphp
                @endif
            @endforeach
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('failed_jobs');
    }
}
