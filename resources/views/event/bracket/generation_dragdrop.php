@extends('event.bracket.layout')

@section('styles')
<!-- Add any additional styles here -->
@endsection

@section('content')
<div class="container" id="app">
   <div class="tournament-bracket tournament-bracket--rounded">
      <!-- Add the v-once directive to prevent Vue from re-rendering the template -->
      <div v-once>
         <!-- Your existing PHP code for initializing $round and $winnersBracket -->
         <?php
         // Assuming you have some logic here to initialize $round and $winnersBracket
         // For example:
         $round = 3;
         $winnersBracket = [
            // Example data structure for a match
            [
               'id' => 1,
               'acname_one' => 'Team A',
               'lastname_one' => 'Player1',
               'firstname_one' => 'Lastname1',
               'acname_two' => 'Team B',
               'lastname_two' => 'Player2',
               'firstname_two' => 'Lastname2',
            ],
            // ... (more matches)
         ];
         ?>

         <!-- Winners Bracket -->
         <div v-for="(round, index) in rounds" :key="index" class="tournament-bracket__round">
            <h3 class="tournament-bracket__round-title">
               Winners Round {{ index + 1 }}
            </h3>
            <draggable v-model="winnersBracket[index]" @end="onDragEnd" class="list-group">
               <div v-for="match in winnersBracket[index]" :key="match.id" class="list-group-item">
                  <!-- Your existing match template here -->
                  <table class="tournament-bracket__table">
                     <tbody class="tournament-bracket__content">
                        <tr class="tournament-bracket__team">
                           <td class="tournament-bracket__country">
                              <abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{ match.acname_one }}</abbr>
                           </td>
                           <td class="tournament-bracket__country">
                              <abbr class="tournament-bracket__code">{{ match.lastname_one != null ? match.lastname_one . ' ' . match.firstname_one : 'TBD' }}</abbr><br>
                              <span class="tournament-bracket__flag flag-icon flag-icon-ca" aria-label="Flag"></span>
                           </td>
                        </tr>
                        <tr class="tournament-bracket__team">
                           <td class="tournament-bracket__country">
                              <abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{ match.acname_two }}</abbr>
                           </td>
                           <td class="tournament-bracket__country">
                              <abbr class="tournament-bracket__code">{{ match.lastname_two != null ? match.lastname_two . ' ' . match.firstname_two : 'TBD' }}</abbr><br>
                              <span class="tournament-bracket__flag flag-icon flag-icon-kz" aria-label="Flag"></span>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </draggable>
         </div>

         <!-- ... (Repeat similar structures for Losers Bracket, Bye Matches, etc.) -->
      </div>
   </div>
</div>

<script>
   new Vue({
      el: '#app',
      data: {
         rounds: <?php echo json_encode(range(1, $round)); ?>,
         winnersBracket: <?php echo json_encode($winnersBracket); ?>,
         // Add other data properties as needed
      },
      methods: {
         onDragEnd: function () {
            // Handle drag and drop logic here, update the bracket order, and make API requests if needed
         },
         // Add other methods as needed
      },
   });
</script>

<!-- Bootstrap JS and Vue Draggable -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.23.0/vuedraggable.umd.min.js"></script>

@endsection
