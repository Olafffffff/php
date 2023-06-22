<form class="space-y-4" method="post" action="index.php">
	    <input type="hidden" name="start" value="1">
            <div class="flex items-center">
                <label for="difficulty" class="mr-2">Wybierz poziom trudności:</label>
                <select id="difficulty" name="difficulty" class="border border-gray-300 rounded px-4 py-2">
                    <option value="easy">Łatwy</option>
                    <option value="hard">Trudny</option>
                </select>
            </div>
            <div class="flex justify-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Start
                </button>
            </div>
</form>
